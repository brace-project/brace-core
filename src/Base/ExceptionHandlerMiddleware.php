<?php


namespace Brace\Core\Base;


use Brace\Core\ExceptionFormatterInterface;
use Brace\Core\ReturnFormatterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ExceptionHandlerMiddleware extends BraceAbstractMiddleware
{

    public function __construct(
        private ?ExceptionFormatterInterface $execptionFormatter = null
    ) {}


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        try {
            return $handler->handle($request);
        } catch (\Exception $ex) {
            $formatter = $this->execptionFormatter ?? new DefaultJsonExceptionFormatter($this->app);
            return $formatter->format($ex);
        } catch (\Error $ex) {
            $formatter = $this->execptionFormatter ?? new DefaultJsonExceptionFormatter($this->app);
            return $formatter->format($ex);
        }
    }
}
