<?php


namespace Brace\Core;


class BraceApp
{
    /**
     * @param array $middlewares
     */
    public function setMiddleWareChain (array $middlewares)
    {

    }


    public function addModule (BraceModule $module)
    {
        $module->register($this);
    }


    public function serve ()
    {

    }
}