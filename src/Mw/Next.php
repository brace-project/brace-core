<?php


namespace Brace\Core\Mw;


use Brace\Core\Base\BraceAbstractMiddleware;
use Brace\Core\BraceApp;
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
    private $chain = [];


    public function __construct (
        public ?RequestHandlerInterface $fallbackRequestHandler = null)
    {

    }

    public function setFallbackRequestHandler (RequestHandlerInterface $fallbackRequestHandler)
    {
        $this->fallbackRequestHandler = $fallbackRequestHandler;
    }

    public function addMiddleWare(MiddlewareInterface $middleware)
    {
        $this->chain[] = $middleware;
    }

    public function setChain(array $chain)
    {
        foreach ($chain as $idx => $elem) {
            if ( ! $elem instanceof MiddlewareInterface)
                throw new \InvalidArgumentException("Element $idx is no instance of MiddlewareInterface");

        }
        $this->chain = $chain;
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $curMw = array_shift($this->chain);
        if ($curMw === null) {
            if ($this->fallbackRequestHandler !== null) {
                return $this->fallbackRequestHandler->handle($request);
            }
        }

        return $curMw->process($request, $this);
    }
}