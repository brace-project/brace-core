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




    private function formatException(\Exception|\Error $e) : array
    {
        $base = [
            "class" => get_class($e),
            "message" => $e->getMessage(),

        ];

        if ($this->showDebugInfo) {
            $base["file"] = $e->getFile() . "(" . $e->getLine() . ")";
            $base["trace"] = array_filter(
                explode("\n", $e->getTraceAsString()),
                function (string $in) {
                    if (str_contains($in, "/vendor/"))
                        return null;
                    return $in;
                }
            );
            $base["trace_full"] = explode("\n", $e->getTraceAsString());
        }
        return $base;
    }

    public function format(\Exception|\Error $e): ResponseInterface
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

        $uri = $this->app->request->getUri();
        if ($e instanceof \Error)
            error_log("Error caught by DefaultJsonExceptionHandler: '{$e->getMessage()}' on Uri \"$uri\" in {$e->getFile()}({$e->getLine()})");

        return $response
            ->withHeader("Content-Type", "application/json");
    }
}
