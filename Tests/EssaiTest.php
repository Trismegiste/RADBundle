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
            $traverser->addVisitor(new \PHPParser_NodeVisitor_NameResolver());
            $traverser->traverse($stmts);
            $traverser->addVisitor(new Visitor\SetterGetter());
            $traverser->addVisitor(new Visitor\ReturnInMethod());
            $traverser->addVisitor(new Visitor\ThrowInMethod());
            $traverser->addVisitor(new Visitor\ThisInMethod());
            // checker les new
            // faire des mockup pour les param objet des methodes
            // traverse
            $traverser->traverse($stmts);
        } catch (\PHPParser_Error $e) {
            echo 'Parse Error: ', $e->getMessage();
        }
    }

    public function testXml()
    {
        $fchPath = __DIR__ . '/Fixtures/Cart.php';
        $code = file_get_contents($fchPath);

        $parser = new \PHPParser_Parser(new \PHPParser_Lexer);

        try {
            $stmts = $parser->parse($code);
            $serializer = new \PHPParser_Serializer_XML();
            $dumpXml = $serializer->serialize($stmts);
            file_put_contents($fchPath . '.xml', $dumpXml);
//            $doc = new \DOMDocument();
//            $doc->loadXML($dumpXml);
//            $xpath = new \DOMXPath($doc);
//            $result = $xpath->evaluate('//node:Expr_Assign/subNode:var//node:Expr_Variable/::parent');
//            foreach ($result as $node) {
//                echo '*' . $node->nodeValue . PHP_EOL;
//            }
        } catch (\PHPParser_Error $e) {
            echo 'Parse Error: ', $e->getMessage();
        }
    }

}
