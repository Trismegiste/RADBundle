<?php

namespace Trismegiste\RADBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Routing\Matcher\Dumper\ApacheMatcherDumper;

/**
 * Generates a unit testing class for a given routes
 *
 * @author trismegiste@voila.fr
 */
class GenerateRoutingTestCommand extends ContainerAwareCommand {

    /**
     * @see Command
     */
    protected function configure() {
        $this
                ->setDefinition(array(
                    new InputArgument('filter', InputArgument::OPTIONAL, 'Filter for the routing', '.*'),
                ))
                ->setDescription('Generates a functional test class based on a collection of route filtered')
                ->setHelp(<<<EOT
The <info>test:generate:routing</info> command generates a functional test class based on a collection of route filtered.

<info>php app/console test:generate:routing [regexp]</info>
EOT
                )
                ->setName('test:generate:routing')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $filter = $input->getArgument('filter');
        $router = $this->getContainer()->get('router');

        $routes = array();
        foreach ($router->getRouteCollection()->all() as $name => $route) {
            if (preg_match("#$filter#", $name)) {
                $testRoute = $route->compile();
                $url = $testRoute->getPattern();
                $url = preg_replace('#(\{([aA-zZ]+)\})#', '\$$2' , $url);
                $requirements = $route->getRequirements();
                if (!isset($requirements['_method'])) 
                    $method = array('get');
                else
                    $method = (is_array($method)) ? $method : array($method);
                
                $routes[$name] = array(
                    'url' => $url,
                    'var' => $testRoute->getVariables(),
                    'method' => $method
                );
            }
        }

        var_dump($routes);
    }

}
