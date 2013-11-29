<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Visitor;

/**
 * Classname tracks classname
 */
class Classname extends CollectorVisitor
{

    protected $name;

    public function enterNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {
            case 'Stmt_Class' :
                $this->collector->setClassname($node->name);
                break;
            case 'Stmt_Namespace' :
                $this->collector->setNamespace($node->name->parts);
                break;
        }
    }

}