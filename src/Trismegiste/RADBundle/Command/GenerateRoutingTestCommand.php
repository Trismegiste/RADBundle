<?php

namespace Trismegiste\RADBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\RADBundle\Generator\RoutingTestGenerator;

/**
 * Generates a unit testing class for a given routes
 */
class GenerateRoutingTestCommand extends ContainerAwareCommand
{

    /**
     * @see Command
     */
    protected function configure()
    {
        $this->
                setDefinition(array(
                    new InputArgument('filter', InputArgument::REQUIRED, 'Filter for routes'),
                    new InputOption('bundle', null, InputOption::VALUE_REQUIRED, 'target bundle')
                ))->
                setDescription('Generates a functional test class on a collection of routes')->
                setHelp("The <info>test:generate:routing</info> command generates a functional test class 
                    based on a collection of routes from a controller or extracted with a filter.\n\n
                    <info>php app/console test:generate:routing filter</info>")->
                setName('test:generate:routing');
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filter = $input->getArgument('filter');

        if (false !== strpos($filter, ':')) {
            list($bundle, $classController) = explode(':', $filter);
            $bundle = $this->getApplication()->getKernel()->getBundle($bundle);
            $classNameController = $classController . 'Controller';
            $routeFilter = $bundle->getNamespace() . '\Controller\\' . $classNameController;
            $router = $this->getContainer()->get('routing.extract.ctrl');
            $testClassName = $classNameController . 'Test';
        } else {
            if (is_null($bundle = $input->getOption('bundle'))) {
                throw new \InvalidArgumentException('bundle option must be defined when using filter');
            }
            $bundle = $this->getApplication()->getKernel()->getBundle($bundle);
            $routeFilter = $filter;
            $router = $this->getContainer()->get('routing.extract.name');
            $testClassName = "RouteStartWith_{$filter}_Test";
        }

        $routes = $router->getExtract($routeFilter);

        if (count($routes)) {

            $generator = new RoutingTestGenerator($this->getContainer()->get('filesystem'), __DIR__ . '/../Resources/skeleton/test');
            $generator->generate($routes, $bundle, $testClassName);

            $output->writeln(sprintf('The new %s.php class file has been created.', $testClassName));
        } else {
            $output->writeln('No route found');
        }
    }

}
