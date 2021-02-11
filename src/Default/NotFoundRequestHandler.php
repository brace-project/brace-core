<?php


namespace Brace\Core\Base;


use Brace\Core\BraceApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NotFoundRequestHandler implements RequestHandlerInterface
{

    /**
     * @var BraceApp
     */
    private $app;

    public function __construct (BraceApp $app)
    {
        $this->app = $app;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $resp = $this->app->responseFactory->createResponse(404, '404 Route undefined: ' . $request->getMethod() . ": " . $request->getUri()->getPath());
    }
}