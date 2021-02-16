<?php


namespace Brace\Core\Base;


use Brace\Core\ReturnFormatterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ExceptionHandlerMiddleware extends BraceAbstractMiddleware
{

    public function __construct(
        private ?ReturnFormatterInterface $execptionFormatter = null
    ) {}


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $formatter = $this->execptionFormatter ?? new DefaultJsonExceptionFormatter($this->app);
        try {
            return $handler->handle($request);
        } catch (\Exception $ex) {
            return $formatter->format($ex);
        }
    }
}