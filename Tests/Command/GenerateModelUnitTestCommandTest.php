<?php

/**
 * RADBundle
 */

namespace Trismegiste\RADBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Trismegiste\RADBundle\Command\GenerateModelUnitTestCommand;

/**
 * GenerateModelUnitTestCommandTest tests GenerateModelUnitTestCommand
 */
class GenerateModelUnitTestCommandTest extends WebTestCase
{

    static protected $class = 'Trismegiste\RADBundle\Tests\Fixtures\Kernel\AppKernel';

    public function testExecute()
    {
        $kernel = self::createKernel();
        $kernel->boot();
        $kernel->registerBundles();

        $application = new Application($kernel);
        $application->add(new GenerateModelUnitTestCommand());

        $command = $application->find('test:generate:class');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'class' => 'TrismegisteRADBundle:Cart'
        ]);

        echo $commandTester->getDisplay();

        // ...
    }

}