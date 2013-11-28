<?php

/*
 * radbundle
 */
namespace Trismegiste\RADBundle\Generator;
/**
 *
 * @author flo
 */
interface ClassMethodInfo
{

    function setMutator(array $methodName);
    function setReturnsFromMethod(array $arr);
    function setThrowsFromMethod(array $arr);
    function setWriteInMethod(array $arr);
    
}
