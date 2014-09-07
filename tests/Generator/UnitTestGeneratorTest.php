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
        $refl = new \ReflectionClass('Trismegiste\RADBundle\TrismegisteRADBundle');
        $rootdir = dirname($refl->getFileName()) . '/Resources/skeleton/test';
        $fs = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $testcase = $this; // smells like js...
        $fs->expects($this->once())
                ->method('dumpFile')
                ->will($this->returnCallback(function($path, $content) use ($testcase) {
                                    eval(str_replace('<?php', '', $content));
                                    $testcase->assertRegExp('#function testCallingMethodTwoObject#', $content);
                                    $testcase->assertRegExp('#function testPropertyName#', $content);
                                    $testcase->assertRegExp('#function testThrowExceptionTwoThrows0#', $content);
                                    $testcase->assertRegExp('#function testThrowExceptionTwoThrows1#', $content);
                                }));
        $generator = new UnitTestGenerator($fs, $rootdir);
        $bundlePath = __DIR__ . '/../Fixtures/Bundle';
        $className = 'Model/CheckConstruct';

        $generator->generate($bundlePath, 'tests\Fixtures\Bundle', $className);
        //$this->assertRegExp('#this->getMock\(\'tests#', $content);
    }

}
