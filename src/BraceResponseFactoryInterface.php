<?php


namespace Brace\Core;


use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

interface BraceResponseFactoryInterface extends ResponseFactoryInterface
{
    public function createResponseWithBody(string $body, int $code = 200) : ResponseInterface;
    
    /**
     * Create a response whose callback runs when the response is emitted.
     *
     * @param \Closure $callback Function receiving an NdjsonStream.
     * @param int $code HTTP status code for the response.
     * @param array $headers Additional HTTP response headers.
     * @return ResponseInterface Deferred streaming response.
     * @see NdjsonStream::send()
     *
     * Example: $factory->createNdjsonResponse(function (NdjsonStream $stream) {
     *     $stream->send(['type' => 'result', 'success' => true]);
     * });
     */
    public function createNdjsonResponse(\Closure $callback, int $code = 200, array $headers = []): ResponseInterface;

    /**
     * Create a response that runs a raw output callback when emitted.
     *
     * @param \Closure $callback Function writing directly to the response output.
     * @param int $code HTTP status code.
     * @param array $headers HTTP response headers.
     * @return ResponseInterface Deferred streaming response.
     * @see self::createNdjsonResponse()
     *
     * Example: $factory->createStreamedResponse(function () { echo 'Hello'; flush(); });
     */
    public function createStreamedResponse(\Closure $callback, int $code = 200, array $headers = []): ResponseInterface;
}
