<?php

/**
 * RADBundle
 */

namespace Trismegiste\RADBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Trismegiste\RADBundle\Command\GenerateRoutingTestCommand;

/**
 * GenerateRoutingTestCommandTest tests the command GenerateRoutingTestCommand
 */
class GenerateRoutingTestCommandTest extends WebTestCase
{

    static protected $class = 'Trismegiste\RADBundle\Tests\Fixtures\Kernel\AppKernel';

    public function testExecute()
    {
        $kernel = self::createKernel();
        $kernel->boot();
        $kernel->registerBundles();

        $mockFiler = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $mockFiler->expects($this->once())
                ->method('dumpFile');

     //   $kernel->getContainer()->set('filesystem', $mockFiler);

        $application = new Application($kernel);
        $application->add(new GenerateRoutingTestCommand());

        $command = $application->find('test:generate:routing');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'filter' => 'Trismegiste\RADBundle\Tests\Fixtures\FuncController'
        ]);

        $output = $commandTester->getDisplay();
        $this->assertRegExp('#Processing Tests/Fixtures/Cart.php#', $output);
    }

}