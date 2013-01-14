<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Generator;

/**
 * Description of UnitTestGenerator
 *
 * @author flo
 */
class UnitTestGenerator
{

    public function generate($str)
    {
        $collector = new ClassCollector();
        $info = $collector->collect($str);

        $fqcnTestedClass = $info['namespace'];
        $fqcnTestedClass[] = $info['classname'];
        $fqcnTestedClass = implode('\\', $fqcnTestedClass);

        ob_start();
        require(__DIR__ . '/../Resources/skeleton/test/SmartTest.php');
        $str = ob_get_contents();
        ob_end_clean();

        return $str;
    }

}
