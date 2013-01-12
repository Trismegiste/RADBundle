<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Visitor;

use Trismegiste\RADBundle\Generator\ClassMethodInfo;

/**
 * MethodVisitor is a
 */
class ReturnInMethod extends CollectorVisitor
{

    protected $currentMethod;
    public $returnStmt = array();

    public function enterNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {

            case 'Stmt_ClassMethod' :
                $this->currentMethod = $node->name;
                break;

            case 'Stmt_Return':
                if (!isset($this->currentMethod)) {
                    throw new \RuntimeException('No current method (odd)');
                }
                $this->returnStmt[$this->currentMethod][] = $node;
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
        $prettyPrinter = new \PHPParser_PrettyPrinter_Zend();
        $result = array();
        foreach ($this->returnStmt as $methodName => $retour) {
            foreach ($retour as $stmt) {
                $result[$methodName][] = $prettyPrinter->prettyPrint(array($stmt));
            }
        }
        $this->collector->setReturnsFromMethod($result);
    }

}