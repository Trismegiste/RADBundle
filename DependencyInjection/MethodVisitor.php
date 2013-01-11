<?php

/*
 * flyingmecha
 */

namespace Trismegiste\RADBundle\DependencyInjection;

/**
 * MethodVisitor is a
 */
class MethodVisitor extends \PHPParser_NodeVisitorAbstract
{

    public $method = array();

    public function enterNode(\PHPParser_Node $node)
    {
        if ($node->getType() == 'Stmt_ClassMethod') {
            if (preg_match('#[s|g]et[A-Z][0-9A-Za-z]#', $methodName = $node->name)) {
                $this->method[] = $methodName;
            }
        }
    }

    public function afterTraverse(array $nodes)
    {
        $property = array();
        foreach ($this->method as $mutator) {
            if ($mutator[0] == 's') {
                foreach ($this->method as $accessor) {
                    if ($accessor == substr_replace($mutator, 'g', 0, 1)) {
                        $property[] = lcfirst(substr($mutator, 3));
                    }
                }
            }
        }

        print_r($property);
    }

}