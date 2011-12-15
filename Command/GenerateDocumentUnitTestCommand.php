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
class GenerateDocumentUnitTestCommand extends GenerateDoctrineCommand
{   
 
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('document', InputArgument::REQUIRED, 'The document class name to unit test (shortcut notation)'),
            ))
            ->setDescription('Generates a unit testing class based on a MongoDB document')
            ->setHelp(<<<EOT
The <info>test:generate:document</info> command generates a unit testing class based on a MongoDB document.

<info>php app/console test:generate:document AcmeBlogBundle:Post</info>
EOT
            )
            ->setName('test:generate:document')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $document = Validators::validateEntityName($input->getArgument('document'));
        list($bundle, $document) = $this->parseShortcutNotation($document);
        
        $bundle   = $this->getApplication()->getKernel()->getBundle($bundle);
        $entityClass = $bundle->getNamespace().'\\Document\\'.$document;
        $metadata = $this->getDocumentMetadata($entityClass);        
        $validator = $this->getContainer()->get('validator');
      
        $generator = new DoctrineTestGenerator($this->getContainer()->get('filesystem'),  __DIR__.'/../Resources/skeleton/test', $validator);
        $generator->generate($bundle, $document, $metadata);
        
        $output->writeln(sprintf(
            'The new %s.php class file has been created under %s.',
            $generator->getClassName(),
            $generator->getClassPath()
        ));
    }
    
   protected function getDocumentMetadata($document)
    {
        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        return $dm->getClassMetadata($document);
    }    
}
