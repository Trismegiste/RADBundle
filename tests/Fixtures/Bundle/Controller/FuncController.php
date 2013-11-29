<?php

namespace tests\Fixtures\Bundle\Controller;

class FuncController
{

    public function indexAction($id)
    {
        return new \Symfony\Component\HttpFoundation\Response('something');
    }

}