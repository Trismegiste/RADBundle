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
        $container->stmts = array($testClass);

        foreach ($info['method'] as $method => $args) {
            $testClass->stmts[] = $this->getMethod($method);
        }

        $prettyPrinter = new \PHPParser_PrettyPrinter_Zend();
        echo $prettyPrinter->prettyPrint(array($container));
    }

}
