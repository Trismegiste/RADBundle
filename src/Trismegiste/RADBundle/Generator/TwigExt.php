<?php

/*
 * RADBundle
 */

namespace Trismegiste\RADBundle\Generator;

/**
 * TwigExt is an extension for twig
 */
class TwigExt extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('ucfirst', 'ucfirst'),
        );
    }

    public function getName()
    {
        return 'radbundle';
    }

}