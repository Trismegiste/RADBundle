<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Trismegiste\RADBundle\Generator\UnitTestGenerator;

/**
 * GenerateModelUnitTestCommand is a command for
 * generation of phpunit class
 *
 * @author florent
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

        $iter = $this->getContainer()->get('classfinder')
                ->files()
                ->in($bundle->getPath())
                ->name($className . '.php');

        foreach ($iter as $fch) {
            $output->writeln("Processing " . $fch->getRelativePathname());

            $code = $fch->getContents();
            $generator = new UnitTestGenerator();
            $testClass = $generator->generate($code, explode('\\', $bundle->getNamespace()));
            $destPath = $bundle->getPath() . '/Tests/' . $className . 'Test.php';
            if (!file_exists($destPath)) {
                $filesystem->dumpFile($destPath, $testClass);
            } else {
                $output->writeln("<error>$destPath already exists</error>");
            }
        }
    }

}