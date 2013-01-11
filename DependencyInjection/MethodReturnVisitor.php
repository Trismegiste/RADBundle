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

    public $method = array();
    protected $currentMethod;
    public $returnStmt = array();

    public function enterNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {

            case 'Stmt_ClassMethod' :
                $this->currentMethod = $methodName = $node->name;
                // store getter and setter
                if (preg_match('#[s|g]et[A-Z][_0-9A-Za-z]#', $methodName)) {
                    $this->method[] = $methodName;
                }
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

        $prettyPrinter = new \PHPParser_PrettyPrinter_Zend();
        foreach ($this->returnStmt as $methodName => $retour) {
            echo $methodName . PHP_EOL;
            foreach ($retour as $stmt) {
                echo '  ' . $prettyPrinter->prettyPrint(array($stmt)) . PHP_EOL;
            }
        }

        print_r($property);
    }

}