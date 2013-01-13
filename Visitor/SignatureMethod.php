<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Visitor;

/**
 * SetterGetter do a match between getter and setter
 */
class SignatureMethod extends CollectorVisitor
{

    protected $method = array();

    public function enterNode(\PHPParser_Node $node)
    {
        if (($node->getType() == 'Stmt_ClassMethod') && ($node->type == 1)) {
            foreach ($node->params as $arg) {
                $typing = '';
                if (!is_null($arg->default)) break;
                if (!empty($arg->type)) {
                    if ($arg->type->getType() == 'Name_FullyQualified') {
                        $typing = implode('\\', $arg->type->parts);
                    }
                }
                $this->method[$node->name][] = $typing;
            }
        }
    }

    public function afterTraverse(array $nodes)
    {
        $this->collector->setSignature($this->method);
    }

}