<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Visitor;

/**
 * SignatureMethod tracks type-hint of methods signature
 */
class SignatureMethod extends CollectorVisitor
{

    protected $method = array();
    protected $currentMethod;

    public function enterNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {
            case 'Stmt_ClassMethod' :
                if ($node->type == 1) {
                    $this->currentMethod = $node->name;
                    $this->method[$node->name] = array();
                    foreach ($node->params as $arg) {
                        if (!is_null($arg->default))
                            break;
                        $typing = '';
                        if (!empty($arg->type)) {
                            if (is_object($arg->type)) {
                                if ($arg->type->getType() == 'Name_FullyQualified') {
                                    $typing = implode('\\', $arg->type->parts);
                                } else {
                                    // à mon avis ça bug avec un NS relatif => bah non ?!?
                                }
                            } else {
                                $typing = $arg->type;
                            }
                        }
                        $this->method[$node->name][$arg->name] = array('type' => $typing);
                    }
                }
                break;

            case 'Expr_MethodCall' :
                if (isset($this->currentMethod)) {
                    if ($node->var->getType() == 'Expr_Variable') {
                        $var = $node->var;
                        if (array_key_exists($var->name, $this->method[$this->currentMethod])) {
                            $this->method[$this->currentMethod][$var->name]['call'][$node->name] = true;
                        }
                    }
                }
                break;
        }
    }

    public function leaveNode(\PHPParser_Node $node)
    {
        if ($node->getType() == 'Stmt_ClassMethod') {
            unset($this->currentMethod);
        }
    }

    public function afterTraverse(array $nodes)
    {
        $this->collector->setSignature($this->method);
    }

}