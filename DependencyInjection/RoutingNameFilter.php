<?php

namespace Trismegiste\RADBundle\DependencyInjection;

use Symfony\Component\Routing\Route;

/**
 * Description of RoutingFilter
 *
 * Algorithm implementation of the Template Method :
 *   Filtering based on a preg_match, think like a SQL "LIKE filter%"
 *
 * @author flo
 */
class RoutingNameFilter extends RoutingFilter {

    protected function isMatching($name, Route $route, $filter) {
        return preg_match("#^$filter#", $name);  // don't be fooled by the ^$ :)
    }

}
