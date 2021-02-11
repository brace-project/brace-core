<?php


namespace Brace\Core\Base;


use Brace\Core\BraceApp;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NotFoundMiddleware extends BraceAbstractMiddleware
{


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $resp = $this->app->responseFactory->createResponse(404, '404 Route undefined: ' . $request->getMethod() . ": " . $request->getUri()->getPath());
        return $resp
            ->withHeader("Content-Type", "text/plain");
    }
}