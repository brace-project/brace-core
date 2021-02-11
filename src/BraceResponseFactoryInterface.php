<?php


namespace Brace\Core;


use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

interface BraceResponseFactoryInterface extends ResponseFactoryInterface
{
    public function createResponseWithBody(string $body, int $code = 200) : ResponseInterface;
}