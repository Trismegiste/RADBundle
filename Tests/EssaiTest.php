<?php

namespace Trismegiste\RADBundle\Tests;

use Trismegiste\RADBundle\Visitor;

/**
 * Description of EssaiTest
 *
 * @author flo
 */
class EssaiTest extends \PHPUnit_Framework_TestCase
{

    public function testEcho()
    {
        $fchPath = __DIR__ . '/Fixtures/Cart.php';
        $code = file_get_contents($fchPath);

        $parser = new \PHPParser_Parser(new \PHPParser_Lexer);

        try {
            $stmts = $parser->parse($code);
            $traverser = new \PHPParser_NodeTraverser();
            $traverser->addVisitor(new Visitor\SetterGetter());
            $traverser->addVisitor(new Visitor\ReturnInMethod());
            $traverser->addVisitor(new Visitor\ThisInMethod());
            $traverser->addVisitor(new Visitor\ThrowInMethod());
            // traverse
            $traverser->traverse($stmts);
        } catch (\PHPParser_Error $e) {
            echo 'Parse Error: ', $e->getMessage();
        }
    }

}
