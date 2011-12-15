<?php

namespace Trismegiste\RADBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\DoctrineBundle\Mapping\MetadataFactory;
use Trismegiste\RADBundle\Generator\DoctrineTestGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;

/**
 * Generates a unit testing class for a given Doctrine entity.
 *
 * @author trismegiste@voila.fr
 */
class GenerateEntityUnitTestCommand extends GenerateDoctrineCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('entity', InputArgument::REQUIRED, 'The entity class name to unit test (shortcut notation)'),
            ))
            ->setDescription('Generates a unit testing class based on a Doctrine entity')
            ->setHelp(<<<EOT
The <info>test:generate:entity</info> command generates a unit testing class based on a Doctrine entity.

<info>php app/console test:generate:entity AcmeBlogBundle:Post</info>
EOT
            )
            ->setName('test:generate:entity')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        $entityClass = $this->getContainer()->get('doctrine')->getEntityNamespace($bundle).'\\'.$entity;
        $metadata = $this->getEntityMetadata($entityClass);
        $bundle   = $this->getApplication()->getKernel()->getBundle($bundle);
        $validator = $this->getContainer()->get('validator');
      
        $generator = new DoctrineTestGenerator($this->getContainer()->get('filesystem'),  __DIR__.'/../Resources/skeleton/test', $validator);
        $generator->generate($bundle, $entity, $metadata[0]);
        
        $output->writeln(sprintf(
            'The new %s.php class file has been created under %s.',
            $generator->getClassName(),
            $generator->getClassPath()
        ));
    }
}
