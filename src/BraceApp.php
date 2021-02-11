<?php


namespace Brace\Core;


use Brace\Core\Base\NotFoundMiddleware;
use Brace\Core\Mw\Next;
use Brace\Router\Router;
use Brace\Router\Type\Route;
use Phore\Di\Container\DiContainer;
use Phore\Di\Container\Producer\DiValue;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class BraceApp
 * @package Brace\Core
 *
 *
 * @property-read Next $pipe
 * @property-read EmitterInferface $emitter
 *
 * From request/response bridge:
 * @property-read ResponseFactoryInterface $responseFactory
 * @property-read ServerRequestInterface $request
 *
 * From brace/mod-router:
 * @property-read Router $router
 * @property-read Route $route    The currently active route as determined by RouterMiddleware
 */
class BraceApp extends DiContainer implements RequestHandlerInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->define("pipe", new DiValue(new Next(new NotFoundMiddleware($this))));
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