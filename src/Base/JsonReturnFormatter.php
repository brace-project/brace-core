<?php


namespace Brace\Core\Base;


use Brace\Core\BraceApp;
use Brace\Core\ReturnFormatterInterface;
use Psr\Http\Message\ResponseInterface;

class JsonReturnFormatter implements ReturnFormatterInterface
{



    public function __construct(
        private BraceApp $app
    ){}

    public function transform($input): ResponseInterface
    {
        if ($input instanceof ResponseInterface)
            return $input;
        $response = $this->app->responseFactory->createResponseWithBody(phore_json_encode($input), 200);
        return $response
            ->withHeader("Content-Type", "application/json");
    }

    public function canHandle($input): bool
    {
        if (is_array($input) || is_object($input))
            return true;
        return false;
    }
}