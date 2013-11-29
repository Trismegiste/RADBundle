<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Generator;

/**
 * Context for visiting and collecting datas on classes
 */
interface ClassMethodInfo
{

    function setMutator(array $methodName);

    function setReturnsFromMethod(array $arr);

    function setThrowsFromMethod(array $arr);

    function setWriteInMethod(array $arr);
}
