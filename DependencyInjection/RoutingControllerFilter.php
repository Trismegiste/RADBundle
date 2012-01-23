<?php

namespace Trismegiste\RADBundle\DependencyInjection;

use Symfony\Component\Routing\Route;

/**
 * Description of RoutingFilter
 *
 * @author flo
 */
class RoutingControllerFilter extends RoutingFilter {

    protected function isMatching($name, Route $route, $filter) {
        $def = $route->getDefaults();
        list($ctrlRoute, $action) = explode('::', $def['_controller']);
        return $filter == $ctrlRoute;
    }

}
