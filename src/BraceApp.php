<?php


namespace Brace\Core;


use Brace\Core\Mw\Next;
use Laminas\Diactoros\Response;
use Phore\Di\Container\DiContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BraceApp extends DiContainer implements RequestHandlerInterface
{
    /**
     * @var Next
     */
    private $pipeline;

    /**
     *
     *
     * @param MiddlewareInterface $middleware
     * @return BraceApp
     */
    public function addMiddleware (MiddlewareInterface $middleware) : self
    {
        $this->pipeline->addMiddleWare($middleware);
        return $this;
    }


    public function addModule (BraceModule $module)
    {
        $module->register($this);
    }


    public function run ()
    {
        $request = $this->resolve("request");
        $emitter = $this->resolve("emitter");
        $response = $this->handle($request);

        $emitter->emit($response);
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->pipeline->handle($request);

    }
}