<?php


namespace Brace\Core\Base;


use Brace\Core\BraceApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CallRouteControllerMw implements RequestHandlerInterface
{

    public function __construct (BraceApp $app)
    {

    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement process() method.
    }
}