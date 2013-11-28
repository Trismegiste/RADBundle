<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Generator;

/**
 * Description of UnitTestGenerator
 */
class UnitTestGenerator
{

    public function generate($str, array $rootNamespace = array())
    {
        $collector = new ClassCollector();
        $info = $collector->collect($str);

        $fqcnTestedClass = $info['namespace'];
        $fqcnTestedClass[] = $info['classname'];
        $fqcnTestedClass = implode('\\', $fqcnTestedClass);

        $namespace4Test = $rootNamespace;
        $namespace4Test[] = 'Tests';
        array_splice($namespace4Test, count($namespace4Test), 0, array_diff_assoc($info['namespace'], $rootNamespace));

        ob_start();
        require(__DIR__ . '/../Resources/skeleton/test/SmartTest.php');
        $str = ob_get_contents();
        ob_end_clean();

        return $str;
    }

}
