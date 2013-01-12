<?php

/*
 * flyingmecha
 */

namespace Trismegiste\RADBundle\Visitor;

use Trismegiste\RADBundle\Generator\ClassMethodInfo;

/**
 * MethodVisitor is a
 */
class ThrowInMethod extends CollectorVisitor
{

    protected $currentMethod;
    protected $filtered = array();

    public function enterNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {

            case 'Stmt_ClassMethod' :
                $this->currentMethod = $node->name;
                break;

            case 'Stmt_Throw':
                if (!isset($this->currentMethod)) {
                    throw new \RuntimeException('No current method (odd)');
                }
                $this->filtered[$this->currentMethod][] = $node;
                break;
        }
    }

    public function leaveNode(\PHPParser_Node $node)
    {
        if ($node->getType() == 'Stmt_ClassMethod') {
            unset($this->currentMethod);
        }
    }

    public function afterTraverse(array $nodes)
    {
        $result = array();
        $prettyPrinter = new \PHPParser_PrettyPrinter_Zend();
        foreach ($this->filtered as $methodName => $retour) {
            foreach ($retour as $stmt) {
                $result[$methodName][] = $prettyPrinter->prettyPrint(array($stmt));
            }
        }
        $this->collector->setThrowsFromMethod($result);
    }

}