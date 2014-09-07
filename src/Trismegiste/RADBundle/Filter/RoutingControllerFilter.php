<?php

namespace Trismegiste\RADBundle\Filter;

use Symfony\Component\Routing\Route;

/**
 * Concret class for the Template Method design pattern
 *
 * Algorithm : Filtering based on one Controller
 *
 * @author flo
 */
class RoutingControllerFilter extends RoutingFilter
{

    protected function isMatching($name, Route $route, $filter)
    {
        $def = $route->getDefaults();
        $matches = [];
        return array_key_exists('_controller', $def) &&
                preg_match('#^([^:]+)::.+$#', $def['_controller'], $matches) &&
                ($filter == $matches[1]);
    }

}
