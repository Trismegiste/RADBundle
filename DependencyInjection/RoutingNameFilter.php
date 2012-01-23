<?php

namespace Trismegiste\RADBundle\DependencyInjection;

use Symfony\Component\Routing\Route;

/**
 * Description of RoutingFilter
 *
 * @author flo
 */
class RoutingNameFilter extends RoutingFilter {

    protected function isMatching($name, Route $route, $filter) {
        return preg_match("#^$filter#", $name);
    }

}
