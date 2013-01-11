<?php

/*
 * flyingmecha
 */

namespace Trismegiste\RADBundle\DependencyInjection;

/**
 * MethodVisitor is a
 */
class MethodReturnVisitor extends \PHPParser_NodeVisitorAbstract
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
        foreach ($this->returnStmt as $methodName => $retour) {
            echo $methodName . PHP_EOL;
            foreach ($retour as $stmt) {
                echo '  ' . $prettyPrinter->prettyPrint(array($stmt)) . PHP_EOL;
            }
        }
    }

}