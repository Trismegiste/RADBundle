<?php

namespace Trismegiste\RADBundle\Tests\Generator;

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
        $fchPath = __DIR__ . '/../Fixtures/Cart.php';
        $code = file_get_contents($fchPath);
        $collector = new \Trismegiste\RADBundle\Generator\ClassCollector();
        $collector->collect($code);
        print_r($collector);
    }

}
