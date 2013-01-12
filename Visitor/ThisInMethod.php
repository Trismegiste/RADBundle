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
    protected $currentAssign;

    public function enterNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {

            case 'Stmt_ClassMethod' :
                $this->currentMethod = $node->name;
                break;

            case 'Expr_Assign':
                $this->currentAssign = $node->var;
                break;

            case 'Expr_Variable':
                if ($node->name == 'this') {
                    // verrif
                    if (!isset($this->currentMethod)) {
                        throw new \RuntimeException('No current method (odd)');
                    }
                    // assign ?
                    if (isset($this->currentAssign)) {
                        // parcours
                        foreach ($this->currentParent as $parent) {
                            if (($parent == $this->currentAssign)) {
                                $this->filtered[$this->currentMethod][] = $this->currentAssign;
                            }
                        }
                    }
                }
                break;
        }

        array_unshift($this->currentParent, $node);
    }

    public function leaveNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {

            case 'Stmt_ClassMethod' :
                unset($this->currentMethod);
                break;

            case 'Expr_Assign' :
                unset($this->currentAssign);
                break;
        }

        array_shift($this->currentParent);
    }

    public function afterTraverse(array $nodes)
    {
        $prettyPrinter = new \PHPParser_PrettyPrinter_Zend();
        foreach ($this->filtered as $methodName => $retour) {
            echo $methodName . PHP_EOL;
            foreach ($retour as $stmt) {
                echo '  ' . $prettyPrinter->prettyPrintExpr($stmt) . PHP_EOL;
            }
        }
    }

}