<?php

namespace Trismegiste\RADBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\DoctrineBundle\Mapping\MetadataFactory;
use Trismegiste\RADBundle\Generator\DoctrineTestGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;

/**
 * Generates a routing testing class
 *
 * @author trismegiste@voila.fr
 */
class GenerateRoutingTestCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this         
            ->setDescription('Generates a functional testing class based on routing')
            ->setHelp(<<<EOT
The <info>test:generate:routing</info> command generates a functional testing class based on routing.

<info>php app/console test:generate:routing <filter></info>
EOT
            )
            ->setName('test:generate:routing')
        ;
    }
 /**
         // Create a new client to browse the application
         $client = static::createClient();

         // Create a new entry in the database
         echo "List products\n";
         $crawler = $client->request('GET', $this->getUrlStart());
         $this->assertEquals(200, $client->getResponse()->getStatusCode());

         echo "Choose to create a $entityKey\n";
         $client->submit($crawler->selectButton('Create')->form(array('key_type' => $entityKey)));
         */
    
    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {             
        /** @var Symfony\Component\Routing\RouteCollection $listRoute */
        $listRoute = $this->getContainer()->get('router')->getRouteCollection()->all();
        foreach($listRoute as $clef => $route)
            $output->writeln($clef . ' ' . $route->getPattern());        
       
    }
}
