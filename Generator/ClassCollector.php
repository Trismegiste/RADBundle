<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Generator;

use Trismegiste\RADBundle\Visitor;

/**
 * Description of ClassCollector
 *
 * @author flo
 */
class ClassCollector implements ClassMethodInfo
{

    protected $mutator;
    protected $returnsInMethod;
    protected $throwsInMethod;
    protected $writeInMethod;

    public function setMutator(array $methodName)
    {
        $this->mutator = $methodName;
    }

    public function setReturnsFromMethod(array $arr)
    {
        $this->returnsInMethod = $arr;
    }

    public function setThrowsFromMethod(array $arr)
    {
        $this->throwsInMethod = $arr;
    }

    public function setWriteInMethod(array $arr)
    {
        $this->writeInMethod = $arr;
    }

    protected function parseClass($code)
    {
        $parser = new \PHPParser_Parser(new \PHPParser_Lexer);

        try {
            $stmts = $parser->parse($code);
            $traverser = new \PHPParser_NodeTraverser();
            $traverser->addVisitor(new \PHPParser_NodeVisitor_NameResolver());
            $traverser->traverse($stmts);
            $traverser->addVisitor(new Visitor\SetterGetter($this));
            $traverser->addVisitor(new Visitor\ReturnInMethod($this));
            $traverser->addVisitor(new Visitor\ThrowInMethod($this));
            $traverser->addVisitor(new Visitor\ThisInMethod($this));
            // checker les new
            // faire des mockup pour les param objet des methodes
            // traverse
            $traverser->traverse($stmts);
        } catch (\PHPParser_Error $e) {
            echo 'Parse Error: ', $e->getMessage();
        }
    }

    public function collect($str)
    {
        $this->parseClass($str);
    }

}
