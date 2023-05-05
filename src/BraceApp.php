<?php


namespace Brace\Core;


use Brace\Assets\AssetSet;
use Brace\Command\Command;
use Brace\Core\Base\BraceAbstractMiddleware;
use Brace\Core\Base\NotFoundMiddleware;
use Brace\Core\Mw\Next;
use Brace\Dbg\BraceDbg;
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
 *
 * From brace/command
 * @property-read Command $command
 *
 * From brace/mod-assets
 * @property-read AssetSet $assets      Manage Assets like js, css or images
 */
class BraceApp extends DiContainer implements RequestHandlerInterface
{
    public readonly EnvironmentType $envoronmentType;

    public function __construct(EnvironmentType $environmentType = null)
    {
        if ($environmentType === null) {
            if (class_exists(BraceDbg::class)) {
                $environmentType = BraceDbg::$environmentType;
            } else {
                $environmentType = EnvironmentType::DEVELOPMENT;
            }
        }
        $this->envoronmentType = $environmentType;

        parent::__construct();
        // Default self-reference
        $this->define("braceApp", new DiValue($this));
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



    public function addModule (BraceModule $module)
    {
        $module->register($this);
    }

    public function redirect(string $target, array $query = []) : ResponseInterface
    {
        $response = $this->responseFactory->createResponse(301);
        if ($query !== []) {
            if ( ! str_contains($target, "?")) {
                $target .= "?";
            } else {
                if ( ! str_ends_with($target, "&"))
                    $target .= "&";
            }
            $target .= http_build_query($query);
        }
        return $response->withHeader("Location", $target);
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
