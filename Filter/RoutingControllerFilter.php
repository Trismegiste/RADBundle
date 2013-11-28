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
        list($ctrlRoute, $action) = explode('::', $def['_controller']);
        return $filter == $ctrlRoute;
    }

}
