<?php

namespace Trismegiste\RADBundle\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Route;

/**
 * Description of RoutingFilter
 * This is a mother class for many ways of reading/filtering/extracting routes
 * See subclasses
 *
 * @author flo
 */
abstract class RoutingFilter {

    protected $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    abstract protected function isMatching($name, Route $route, $filter);

    public function getExtract($filter) {
        $routes = array();
        foreach ($this->router->getRouteCollection()->all() as $name => $defRoute) {
            $testRoute = $defRoute->compile();
            if ($this->isMatching($name, $testRoute->getRoute(), $filter)) {
                $url = $testRoute->getPattern();
                $url = preg_replace('#(\{([_aA-zZ]+)\})#', '\$$2', $url);
                $requirements = $defRoute->getRequirements();
                if (!isset($requirements['_method'])) {
                    $method = array('GET');
                } else {
                    $method = explode('|', $requirements['_method']);
                }

                $listVar = array();
                foreach ($testRoute->getVariables() as $varName) {
                    $listVar[$varName] = isset($requirements[$varName]) ? $requirements[$varName] : '*';
                }

                foreach ($method as $http) {
                    $routes[$name . '_' . strtolower($http)] = array('name' => $name, 'url' => $url, 'var' => $listVar, 'method' => $http);
                }
            }
        }
        
        return $routes;
    }

}
