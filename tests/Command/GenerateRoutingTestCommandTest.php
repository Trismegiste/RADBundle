<?php

/**
 * RADBundle
 */

namespace tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Trismegiste\RADBundle\Command\GenerateRoutingTestCommand;

/**
 * GenerateRoutingTestCommandTest tests the command GenerateRoutingTestCommand
 */
class GenerateRoutingTestCommandTest extends WebTestCase
{

    public function testExecute()
    {
        $kernel = self::createKernel();
        $kernel->boot();
        $kernel->registerBundles();

        $mockFiler = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $mockFiler->expects($this->once())
                ->method('dumpFile');

        $kernel->getContainer()->set('filesystem', $mockFiler);

        $application = new Application($kernel);
        $application->add(new GenerateRoutingTestCommand());

        $command = $application->find('test:generate:routing');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'filter' => 'AlphaBundle:Func'
        ]);

        $output = $commandTester->getDisplay();
        $this->assertRegExp('#FuncControllerTest.php class file#', $output);
    }

}