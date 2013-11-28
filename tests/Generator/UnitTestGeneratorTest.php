<?php

/*
 * radbunde
 */

namespace tests\Generator;

use Trismegiste\RADBundle\Generator\UnitTestGenerator;

/**
 * UnitTestGeneratorTest tests the generator of unit test for a class
 */
class UnitTestGeneratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests the mockup injected in constructor
     */
    public function testGenerate()
    {
        $generator = new UnitTestGenerator();
        $fchPath = __DIR__ . '/../Fixtures/CheckConstruct.php';
        $code = file_get_contents($fchPath);
        $content = $generator->generate($code);
        $this->assertRegExp('#this->getMock\(\'Trismegiste#', $content);
    }

}
