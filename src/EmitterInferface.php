<?php


namespace Brace\Core;


use Psr\Http\Message\ResponseInterface;

interface EmitterInferface
{
    /**
     * @param ResponseInterface $response
     */
    public function emit(ResponseInterface $response) : void;
}