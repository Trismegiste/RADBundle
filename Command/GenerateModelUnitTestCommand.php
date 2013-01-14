<?php

/*
 * flyingmecha
 */

namespace Trismegiste\RADBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Trismegiste\RADBundle\Generator\UnitTestGenerator;

/**
 * GenerateModelUnitTest is a ...
 *
 * @author florent
 */
class GenerateModelUnitTestCommand extends Command
{

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
                ->setDefinition(array(
                    new InputArgument('model', InputArgument::REQUIRED, 'The model class name to unit test (shortcut notation)'),
                ))
                ->setDescription('Generates a unit testing class based on a model class')
                ->setHelp(<<<EOT
The <info>test:generate:model</info> command generates a unit testing class based on a model class.

<info>php app/console test:generate:model AcmeBlogBundle:Post</info>
EOT
                )
                ->setName('test:generate:model')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modelClass = $input->getArgument('model');
        list($bundleName, $className) = explode(':', $modelClass);
        $bundle = $this->getApplication()->getKernel()->getBundle($bundleName);
        $fchPath = $bundle->getPath() . '/' . $className . '.php';
        $output->writeln("Processing $fchPath");
        $code = file_get_contents($fchPath);
        $generator = new UnitTestGenerator();
        $testClass = $generator->generate($code, explode('\\', $bundle->getNamespace()));
    }

}