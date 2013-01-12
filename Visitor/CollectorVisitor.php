<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Visitor;

use Trismegiste\RADBundle\Generator\ClassMethodInfo;

/**
 * CollectorVisitor is a visitor which collect data
 */
abstract class CollectorVisitor extends \PHPParser_NodeVisitorAbstract
{

    protected $collector;

    public function __construct(ClassMethodInfo $coll)
    {
        $this->collector = $coll;
    }

}