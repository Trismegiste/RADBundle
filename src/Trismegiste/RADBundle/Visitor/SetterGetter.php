<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Visitor;

/**
 * SetterGetter does a match between getter and setter
 */
class SetterGetter extends CollectorVisitor
{

    protected $method = array();

    public function enterNode(\PHPParser_Node $node)
    {
        if (($node->getType() == 'Stmt_ClassMethod') && ($node->type == 1)) {
            if (preg_match('#[s|g]et[A-Z][0-9A-Za-z]#', $methodName = $node->name)) {
                // @todo Add a check on number of param for a seetter ( only one )
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

        $this->collector->setMutator($property);
    }

}