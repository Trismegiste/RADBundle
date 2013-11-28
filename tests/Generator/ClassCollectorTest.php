<?php

namespace tests\Generator;

use Trismegiste\RADBundle\Visitor;

/**
 * Test ClassCollector
 *
 * @author flo
 */
class ClassCollectorTest extends \PHPUnit_Framework_TestCase
{

    public function testCollect()
    {
        $fchPath = __DIR__ . '/../Fixtures/Bundle/Model/Cart.php';
        $code = file_get_contents($fchPath);
        $collector = new \Trismegiste\RADBundle\Generator\ClassCollector();
        $info = $collector->collect($code);

        $this->assertEquals(array(
            'classname' => 'Cart',
            'namespace' => array(
                0 => 'tests',
                1 => 'Fixtures',
                2 => 'Bundle',
                3 => 'Model'
            ),
            'mutator' => array(
                0 => 'address',
            ),
            'return' => array(
                'getAddress' => array(
                    0 => 'return $this->address;',
                ),
                'untested' => array(
                    0 => 'return 4;',
                ),
            ),
            'throw' => array(
                'setAddress' => array(
                    0 => 'throw new \\InvalidArgumentException();',
                ),
            ),
            'write' => array(
                '__construct' => array(
                    0 => '$this->address',
                ),
                'addItem' => array(
                    0 => '$this->row[]',
                ),
                'setAddress' => array(
                    0 => '$this->address2',
                    1 => '$this->address',
                ),
                'inc' => array(
                    0 => '$this->cmpt',
                ),
                'addAddress' => array(
                    0 => '$this->address',
                ),
                'negativeFalse' => array(
                    0 => '$row[$this->cmpt]',
                ),
            ),
            'method' =>
            array(
                '__construct' => array(
                    'addr' => array('type' => '',),
                ),
                'addItem' => array(
                    'qt' => array('type' => '',),
                    'pro' => array('type' => 'tests\\Fixtures\\Bundle\\Model\\Product',),
                ),
                'setAddress' => array(
                    'adr' => array('type' => '',),
                ),
                'addAddress' => array(
                    'str' => array('type' => '',),
                ),
                'calling' => array(
                    'doc' => array(
                        'type' => 'tests\\Fixtures\\Bundle\\Model\\Inner',
                        'call' => array('save' => true)
                    )
                ),
                'getAddress' => array(),
                'inc' => array(),
                'negativeFalse' => array()
            ),
                )
                , $info);
    }

}
