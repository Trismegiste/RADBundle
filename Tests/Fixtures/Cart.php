<?php

namespace Trismegiste\RADBundle\Tests\Fixtures;

class Cart
{

    public $info = '';
    protected $address;
    private $row = array();

    public function __construct($addr)
    {
        $this->address = $addr;
    }

    public function addItem($qt, Product $pro)
    {
        $this->row[] = array('qt' => $qt, 'item' => $pro);
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($adr)
    {
        $this->address = $adr;
    }

}
