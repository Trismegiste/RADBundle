<?php

namespace Trismegiste\RADBundle\Tests\Fixtures;

class Cart
{

    public $info = '';
    protected $address;
    protected $address2;
    private $row = array();
    protected $cmpt = 0;

    public function __construct($addr)
    {
        $this->address = $addr;
    }

    public function addItem($qt, Product $pro, $info = '')
    {
        $this->row[] = array('qt' => $qt, 'item' => $pro);
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($adr)
    {
        if (empty($adr)) {
            throw new \InvalidArgumentException();
        }
        $this->address2 = $this->address = $adr;
    }

    public function inc()
    {
        $this->cmpt++;
    }

    public function addAddress($str)
    {
        $this->address .= $str;
    }

    public function negativeFalse()
    {
        $row[$this->cmpt] = 42;
    }

    protected function untested()
    {
        return 4;
    }

    public function calling(Inner $doc)
    {
        $doc->save();
    }

}
