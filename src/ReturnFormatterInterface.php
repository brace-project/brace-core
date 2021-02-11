<?php


namespace Brace\Core;


use Psr\Http\Message\ResponseInterface;

interface ReturnFormatterInterface
{
    /**
     * Transform any return value from controller to a response
     *
     * If input is already a Response leave it untouched
     *
     * @param $input
     * @return ResponseInterface
     */
    public function transform($input) : ResponseInterface;
}