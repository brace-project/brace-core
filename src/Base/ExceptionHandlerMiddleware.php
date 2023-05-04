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
        // Throw Error instead of triggering a Error
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new \Error($errstr . " in $errfile lineno $errline", $errno);
        });


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
