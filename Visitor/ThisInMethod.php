<?php

/*
 * flyingmecha
 */

namespace Trismegiste\RADBundle\Visitor;

/**
 * MethodVisitor is a
 */
class ThisInMethod extends \PHPParser_NodeVisitorAbstract
{

    protected $currentMethod;
    protected $currentParent = array();
    public $filtered = array();

    public function enterNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {

            case 'Stmt_ClassMethod' :
                $this->currentMethod = $node->name;
                break;

            case 'Expr_Variable':
                if (!isset($this->currentMethod)) {
                    throw new \RuntimeException('No current method (odd)');
                }
                if($node->name == 'this') {
                    $this->filtered[$this->currentMethod][] = $this->currentParent[0];
                }
                break;
        }

        array_unshift($this->currentParent, $node);
    }

    public function leaveNode(\PHPParser_Node $node)
    {
        if ($node->getType() == 'Stmt_ClassMethod') {
            unset($this->currentMethod);
        }

        array_shift($this->currentParent);
    }

    public function afterTraverse(array $nodes)
    {
        $prettyPrinter = new \PHPParser_PrettyPrinter_Zend();
        foreach ($this->filtered as $methodName => $retour) {
            echo $methodName . PHP_EOL;
            foreach ($retour as $stmt) {
                echo '  ' . $prettyPrinter->prettyPrint(array($stmt)) . PHP_EOL;
            }
        }
    }

}