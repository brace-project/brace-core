<?php


namespace Brace\Core;


use Brace\Core\Mw\Next;
use Laminas\Diactoros\Response;
use Phore\Di\Container\DiContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class BraceApp
 * @package Brace\Core
 *
 * @property Router $router
 * @property Next $pipe
 */
class BraceApp extends DiContainer implements RequestHandlerInterface
{
    public function __construct()
    {
        $this->define("pipe", new DiValue(new Next()));
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