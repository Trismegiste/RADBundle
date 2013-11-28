<?php

namespace tests\Fixtures\Bundle\Controller;

class FuncController
{

    public function indexAction()
    {
        return new \Symfony\Component\HttpFoundation\Response('something');
    }

}