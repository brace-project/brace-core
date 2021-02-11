<?php


namespace Brace\Core;


use Brace\Assets\AssetSet;
use Brace\Core\Base\BraceAbstractMiddleware;
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
 * @property-read BraceResponseFactoryInterface $responseFactory
 * @property-read ServerRequestInterface $request
 *
 * From brace/mod-router:
 * @property-read Router $router
 * @property-read Route $route    The currently active route as determined by RouterMiddleware
 *
 * From brace/mod-assets
 * @property-read AssetSet $assets      Manage Assets like js, css or images
 */
class BraceApp extends DiContainer implements RequestHandlerInterface
{
    public function __construct()
    {
        parent::__construct();

    }


    /**
     * Define the MiddleWare Chain to process the request
     *
     * <example>
     * $app->setPipe([
     *      new RouterEvalMiddleware(),
     *      new RouterDispatchMiddleware()
     * ]);
     * </example>
     *
     * @param array $middlewares
     */
    public function setPipe(array $middlewares)
    {
        $this->define("pipe", new DiValue($pipe = new Next()));
        foreach ($middlewares as $idx => $middleware) {
            if ( ! $middleware instanceof MiddlewareInterface)
                throw new \InvalidArgumentException("Element '$idx' is not an instance of MiddlewareInterface." . phore_var($middleware));
            if ($middleware instanceof BraceAbstractMiddleware)
                $middleware->_setApp($this);
            $pipe->addMiddleWare($middleware);
        }
    }

    public function __get($name)
    {
        return $this->resolve($name);
    }

    public function __set($name, $val)
    {
        throw new \InvalidArgumentException("Trying to set '$name' on app. Use define() to inject something.");
    }

    public function __isset ($name)
    {
        return $this->has($name);
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
        return $this->pipe->handle($request);

    }
}