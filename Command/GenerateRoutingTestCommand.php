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
class GenerateRoutingTestCommand extends ContainerAwareCommand
{

    /**
     * @see Command
     */
    protected function configure()
    {
        $this->
            setDefinition(array(new InputArgument('controller', InputArgument::REQUIRED, 'Shortcut for controller')))->
            setDescription('Generates a functional test class on a controller')->
            setHelp("The <info>test:generate:routing</info> command generates a functional test class 
                    based on a collection of route coming from a controller.\n\n
                    <info>php app/console test:generate:routing AcmeBlogBundle:Front</info>")->
            setName('test:generate:routing');

    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $controllerShortcut = $input->getArgument('controller');
        $router = $this->getContainer()->get('router');

        list($bundle, $classController) = explode(':', $controllerShortcut);

        $bundle = $this->getApplication()->getKernel()->getBundle($bundle);
        $classNameController = $classController . 'Controller';
        $fqcnController = $bundle->getNamespace() . '\Controller\\' . $classNameController;

        $routes = array();
        foreach ($router->getRouteCollection()->all() as $name => $route) {
            $testRoute = $route->compile();
            $def = $testRoute->getRoute()->getDefaults();
            $ctrlRoute = explode('::', $def['_controller']);            
            if ($fqcnController == $ctrlRoute[0]) {
                list($ctrlRoute, $action) = $ctrlRoute;
                $url = $testRoute->getPattern();
                $url = preg_replace('#(\{([_aA-zZ]+)\})#', '\$$2', $url);
                $requirements = $route->getRequirements();
                if (!isset($requirements['_method'])) {
                    $method = array('get');
                } else {
                    $method = explode('|', strtolower($requirements['_method']));
                }

                $listVar = array();
                foreach ($testRoute->getVariables() as $varName) {
                    $listVar[$varName] = isset($requirements[$varName]) ? $requirements[$varName] : '*';
                }

                foreach ($method as $http) {
                    $routes[$name . '_' . $http] = array('url' => $url, 'var' => $listVar, 'method' => $http);
                }
            }
        }

        if (count($routes)) {
            $filePath = sprintf("%s/Tests/Controller/%sTest.php", $bundle->getPath(), $classNameController);
            var_dump($routes);
            /*
             $generator = new RoutingTestGenerator($this->get('filesystem'),  __DIR__.'/../Resources/skeleton/test');
             $generator->generate($bundle, $routes);

             $output->writeln(sprintf(
             'The new %s.php class file has been created under %s.',
             $generator->getClassName(),
             $generator->getClassPath()
             )); */
        } else {
            $output->writeln('No route found');
        }
    }

}
