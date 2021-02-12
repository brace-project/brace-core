<?php


namespace Brace\Core\Base;


use Brace\Core\BraceApp;
use Brace\Core\ReturnFormatterInterface;
use Psr\Http\Message\ResponseInterface;

class JsonReturnFormatter implements ReturnFormatterInterface
{

    /**
     * @var BraceApp
     */
    private $app;

    public function __construct(BraceApp $app)
    {
        $this->app = $app;
    }

    public function transform($input): ResponseInterface
    {
        if ($input instanceof ResponseInterface)
            return $input;
        $response = $this->app->responseFactory->createResponse();
        return $response
            ->withHeader("Content-Type", "application/json")
            ->withBody(phore_json_encode($response));
    }

    public function canHandle($input): bool
    {
        if (is_array($input) || is_object($input))
            return true;
        return false;
    }
}