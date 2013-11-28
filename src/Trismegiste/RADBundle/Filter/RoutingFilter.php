<?php

namespace Trismegiste\RADBundle\Filter;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Route;

/**
 * Description of RoutingFilter
 * This is a mother class for many ways of reading/filtering/extracting routes
 * 
 * Design pattern : Template Method (See subclasses)
 *
 * @author flo
 */
abstract class RoutingFilter
{

    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * This is the method to implement : 
     * Return true xor false if the $route named $name matches the string $filter
     * You can filter the way you want with this 3 params
     */
    abstract protected function isMatching($name, Route $route, $filter);

    public function getExtract($filter)
    {
        $routes = array();
        foreach ($this->router->getRouteCollection()->all() as $name => $defRoute) {

            if ($this->isMatching($name, $defRoute, $filter)) {
                $url = $defRoute->getPath();
                $url = preg_replace('#(\{([_aA-zZ]+)\})#', '\$$2', $url);
                $requirements = $defRoute->getRequirements();
                if (!isset($requirements['_method'])) {
                    $method = array('GET');
                } else {
                    $method = explode('|', $requirements['_method']);
                }

                $listVar = array();
                $compiled = $defRoute->compile();
                foreach ($compiled->getVariables() as $varName) {
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
