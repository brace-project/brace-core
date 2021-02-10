<?php


namespace Brace\Core;


use Phore\Di\Container\DiContainer;

class BraceApp extends DiContainer
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