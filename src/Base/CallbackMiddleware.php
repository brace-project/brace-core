<?php

namespace Brace\Core\Base;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CallbackMiddleware implements MiddlewareInterface
{

    public function __construct(
        private \Closure $fn
    ){}
        
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return ($this->fn)($request, $handler);
            
    }
}