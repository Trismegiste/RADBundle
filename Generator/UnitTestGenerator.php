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

    protected $testClass;

    protected function getMethod($name)
    {
        
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

        $prettyPrinter = new \PHPParser_PrettyPrinter_Zend();
        echo $prettyPrinter->prettyPrint(array($container));
    }

}
