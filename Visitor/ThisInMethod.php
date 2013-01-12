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
    protected $currentWrite;

    public function enterNode(\PHPParser_Node $node)
    {
        $op = $node->getType();

        switch ($op) {

            case 'Stmt_ClassMethod' :
                $this->currentMethod = $node->name;
                break;

            case 'Expr_Variable':
                if ($node->name == 'this') {
                    // verrif
                    if (!isset($this->currentMethod)) {
                        throw new \RuntimeException('No current method (odd)');
                    }
                    // assign ?
                    foreach ($this->currentWrite as $curr) {
                        // parcours
                        foreach ($this->currentParent as $parent) {
                            if (($parent == $curr)) {
                                $this->filtered[$this->currentMethod][] = $curr;
                            }
                        }
                    }
                }
                break;

            case 'Expr_PostInc' :
            case 'Expr_PostDec' :
            case 'Expr_PreInc' :
            case 'Expr_PreDec' :
                $this->currentWrite[$op] = $node->var;
                break;

            default:
                if (preg_match('#^Expr_Assign#', $op)) {
                    $this->currentWrite[$op] = $node->var;
                }
        }

        array_unshift($this->currentParent, $node);
    }

    public function leaveNode(\PHPParser_Node $node)
    {
        $op = $node->getType();
        switch ($op) {

            case 'Stmt_ClassMethod' :
                unset($this->currentMethod);
                break;

            case 'Expr_PostInc' :
            case 'Expr_PostDec' :
            case 'Expr_PreInc' :
            case 'Expr_PreDec' :
                unset($this->currentWrite[$op]);
                break;

            default:
                if (preg_match('#^Expr_Assign#', $op)) {
                    unset($this->currentWrite[$op]);
                }
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