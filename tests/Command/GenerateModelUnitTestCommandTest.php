<?php

/**
 * RADBundle
 */

namespace tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Trismegiste\RADBundle\Command\GenerateModelUnitTestCommand;

/**
 * GenerateModelUnitTestCommandTest tests GenerateModelUnitTestCommand
 */
class GenerateModelUnitTestCommandTest extends WebTestCase
{

    static protected $class = 'tests\Fixtures\Kernel\AppKernel';

    public function testExecute()
    {
        $kernel = self::createKernel();
        $kernel->boot();
        $kernel->registerBundles();
        echo $kernel->getBundle('AlphaBundle')->getPath();

        $mockFiler = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $mockFiler->expects($this->once())
                ->method('dumpFile');

        $kernel->getContainer()->set('filesystem', $mockFiler);

        $application = new Application($kernel);
        $application->add(new GenerateModelUnitTestCommand());

        $command = $application->find('test:generate:class');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'class' => 'AlphaBundle:Model/Cart'
        ]);

        $output = $commandTester->getDisplay();
        echo $output;
        $this->assertRegExp('#Processing Model/Cart#', $output);
    }

}