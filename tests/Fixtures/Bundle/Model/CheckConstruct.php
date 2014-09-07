<?php

namespace tests\Fixtures\Bundle\Model;

class CheckConstruct
{

    protected $name;

    public function __construct(Param $obj)
    {
        
    }

    public function methodZero()
    {
        
    }

    public function methodOneScalar($scalar)
    {
        
    }

    public function methodOneObject(\stdClass $obj)
    {
        
    }

    public function methodTwoObject(\Iterator $it, \SplObjectStorage $dummy)
    {
        
    }

    public function setName($str)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function throwExceptionOne($param1, $param2)
    {
        throw new \InvalidArgumentException();
        throw new \RuntimeException();
    }

    public function throwExceptionTwo(\Iterator $param1, array $param2)
    {
        throw new \LogicException();
        throw new \DomainException();
    }

    public function callRelative(Submodel\Info $param)
    {
        
    }

}
