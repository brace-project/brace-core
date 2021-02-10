<?php


namespace Brace\Core;


use Brace\Core\Mw\Next;
use Laminas\Diactoros\Response;
use Phore\Di\Container\DiContainer;
use Psr\Http\Server\MiddlewareInterface;

class BraceApp extends DiContainer
{
    /**
     * @var Next
     */
    private $pipeline;

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
        $request = $this->resolve("request");
        $emitter = $this->resolve("emitter");
        $response = $this->pipeline->handle($request);

        $emitter->emit($response);
    }
}