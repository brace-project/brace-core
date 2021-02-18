<?php


namespace Brace\Core;


use Psr\Http\Message\ResponseInterface;

interface ExceptionFormatterInterface
{

    public function format(\Exception|\Error $e) : ResponseInterface;
}