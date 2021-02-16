<?php


namespace Brace\Core\Base;


use Brace\Core\BraceApp;
use Brace\Core\ExceptionFormatterInterface;
use Psr\Http\Message\ResponseInterface;

class DefaultJsonExceptionFormatter implements ExceptionFormatterInterface
{

    public function __construct(
        private BraceApp $app,
        private bool $showDebugInfo = true
    ) {}




    private function formatException(\Exception $e) : array
    {
        $base = [
            "class" => get_class($e),
            "message" => $e->getMessage(),
        ];
        if ($this->showDebugInfo) {
            $base["file"] = $e->getFile() . " [Line:" . $e->getLine() . "]";
        }
        return $base;
    }

    public function format(\Exception $e): ResponseInterface
    {
        $errors = [$this->formatException($e)];
        $prev = $e;
        while (($prev = $prev->getPrevious()) !== null) {
            $errors[] = $this->formatException($prev);
        }

        $data = [
            "error" => [
                "errors" => $errors,
                "code" => $e->getCode(),
                "message" => $e->getMessage()
            ]
        ];

        $response = $this->app->responseFactory->createResponseWithBody(json_encode($data, JSON_PRESERVE_ZERO_FRACTION|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE), 500);
        return $response
            ->withHeader("Content-Type", "application/json");
    }
}