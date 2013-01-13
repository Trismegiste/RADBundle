<?php

/*
 * radbunde
 */

namespace Trismegiste\RADBundle\Tests\Generator;

use Trismegiste\RADBundle\Generator\UnitTestGenerator;

/**
 * Description of UnitTestGeneratorTest
 *
 * @author flo
 */
class UnitTestGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function testGenerate()
    {
        $generator = new UnitTestGenerator();
        $fchPath = __DIR__ . '/../Fixtures/CheckConstruct.php';
        $code = file_get_contents($fchPath);
        $generator->generate($code);
    }

}

