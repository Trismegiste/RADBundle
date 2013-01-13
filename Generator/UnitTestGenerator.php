<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Generator;

/**
 * Description of UnitTestGenerator
 *
 * @author flo
 */
class UnitTestGenerator
{

    protected $factory;
    protected $info;

    protected function getMethod($name)
    {
        return $this->factory->method('test' . ucfirst($name))->getNode();
    }

    public function generate($str)
    {
        $collector = new ClassCollector();
        $info = $collector->collect($str);
        $this->factory = $factory = new \PHPParser_BuilderFactory;
        $testClass = $this->factory
                        ->class($info['classname'] . 'Test')
                        ->extend('\PHPUnit_Framework_TestCase')->getNode();

        $container = new \PHPParser_Node_Stmt_Namespace(new \PHPParser_Node_Name($info['namespace']));
        $fqcn = $info['namespace'];
        $fqcn[] = $info['classname'];
        $container->stmts[] = new \PHPParser_Node_Stmt_Use(array(new \PHPParser_Node_Stmt_UseUse(new \PHPParser_Node_Name($fqcn))));
        $container->stmts[] = $testClass;
        
        $testClass->stmts[] =$this->factory->method('getInstance')
                ->addStmt(new \PHPParser_Node_Expr_Assign(
                        new \PHPParser_Node_Expr_Variable('obj'),
                        new \PHPParser_Node_Expr_New(new \PHPParser_Node_Name($info['classname']))
                        ))
                ->addStmt(new \PHPParser_Node_Stmt_Return(new \PHPParser_Node_Expr_Variable('obj')))
                ->getNode();

        foreach ($info['method'] as $method => $args) {
            $testClass->stmts[] = $this->getMethod($method);
        }

        $prettyPrinter = new \PHPParser_PrettyPrinter_Zend();
        echo $prettyPrinter->prettyPrint(array($container));
    }

}
