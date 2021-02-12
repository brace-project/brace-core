<?php


namespace Brace\Core;


use Psr\Http\Message\ResponseInterface;

interface ReturnFormatterInterface
{

    /**
     * Determine if this Return Formatter can handle
     * the input
     *
     * @param $input
     * @return bool
     */
    public function canHandle($input) : bool;

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