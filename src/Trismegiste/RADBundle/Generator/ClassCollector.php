<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Generator;

use Trismegiste\RADBundle\Visitor;

/**
 * Collects data on classes
 */
class ClassCollector implements ClassMethodInfo
{

    protected $publicSignature;
    protected $mutator;
    protected $returnsInMethod;
    protected $throwsInMethod;
    protected $writeInMethod;
    protected $namespace;
    protected $classname;

    public function setSignature(array $arr)
    {
        $this->publicSignature = $arr;
    }

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

    public function setClassname($str)
    {
        $this->classname = $str;
    }

    public function setNamespace($str)
    {
        $this->namespace = $str;
    }

    protected function parseClass($code)
    {
        $parser = new \PHPParser_Parser(new \PHPParser_Lexer);

        try {
            $stmts = $parser->parse($code);
            $traverser = new \PHPParser_NodeTraverser();
            $traverser->addVisitor(new \PHPParser_NodeVisitor_NameResolver());
            $traverser->traverse($stmts);
            $traverser->addVisitor(new Visitor\Classname($this));
            $traverser->addVisitor(new Visitor\SetterGetter($this));
            $traverser->addVisitor(new Visitor\SignatureMethod($this));
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
        
        return array(
            'classname' => $this->classname,
            'namespace' => $this->namespace,
            'mutator' => $this->mutator,
            'return' => $this->returnsInMethod,
            'throw' => $this->throwsInMethod,
            'write' => $this->writeInMethod,
            'method' => $this->publicSignature
        );
    }

}
