<?php


namespace Brace\Core\Mw;


use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Next implements MiddlewareInterface, RequestHandlerInterface
{

    /**
     *
     * @var MiddlewareInterface[]
     */
    public $chain = [];

    /**
     * @var RequestHandlerInterface
     */
    private $fallbackRequestHandler;

    public function __construct (RequestHandlerInterface $fallbackRequestHandler)
    {
        $this->fallbackRequestHandler = $fallbackRequestHandler;
    }

    public function setFallbackRequestHandler (RequestHandlerInterface $fallbackRequestHandler)
    {
        $this->fallbackRequestHandler = $fallbackRequestHandler;
    }

    public function addMiddleWare(MiddlewareInterface $middleware)
    {
        $this->chain[] = $middleware;
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $curMw = array_shift($this->chain);
        if ($curMw === null)
            return $this->fallbackRequestHandler->handle($request);
        return $curMw->process($request, $this);
    }
}