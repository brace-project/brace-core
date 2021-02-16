<?php


namespace Brace\Core\Base;


use Brace\Core\BraceApp;
use Brace\Core\ExceptionFormatterInterface;
use Psr\Http\Message\ResponseInterface;

class DefaultJsonExceptionFormatter implements ExceptionFormatterInterface
{

    public function __construct(
        private BraceApp $app
    ) {}


    public function format(\Exception $e): ResponseInterface
    {
        $data = [
            "success" => false,
            "status" => "Exception",
            "e_msg" => $e->getMessage()
        ];

        $response = $this->app->responseFactory->createResponseWithBody(json_encode($data, JSON_PRESERVE_ZERO_FRACTION|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE), 500);
        return $response
            ->withHeader("Content-Type", "application/json");
    }
}