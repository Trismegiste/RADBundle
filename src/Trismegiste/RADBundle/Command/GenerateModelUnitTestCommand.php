<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Trismegiste\RADBundle\Generator\UnitTestGenerator;

/**
 * GenerateModelUnitTestCommand is a command for
 * generation of phpunit class
 */
class GenerateModelUnitTestCommand extends ContainerAwareCommand
{

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
                ->setDefinition(array(
                    new InputArgument('class', InputArgument::REQUIRED, 'The class\' name to unit test (shortcut notation)'),
                ))
                ->setDescription('Generates a unit testing class based on a class (with getter/setter)')
                ->setHelp(<<<EOT
The <info>test:generate:class</info> command generates a unit testing concrete class (can be a model class or not).
Tests getters and setters, thrown exceptions, generates mockup for constructor and methods calling...

<info>php app/console test:generate:class AcmeBlogBundle:Post</info>
EOT
                )
                ->setName('test:generate:class')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filesystem = $this->getContainer()->get('filesystem');

        $modelClass = $input->getArgument('class');
        list($bundleName, $className) = explode(':', $modelClass);
        $bundle = $this->getApplication()->getKernel()->getBundle($bundleName);

        $output->writeln("Processing $className");

        $generator = new UnitTestGenerator($filesystem, __DIR__ . '/../Resources/skeleton/test');
        try {
            $generator->generate($bundle->getPath(), $bundle->getNamespace(), $className);
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

}