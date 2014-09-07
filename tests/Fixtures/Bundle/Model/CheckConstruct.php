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

}
