<?php


namespace Brace\Core\Base;


use Brace\Core\BraceApp;
use Psr\Http\Server\MiddlewareInterface;

abstract class BraceAbstractMiddleware implements MiddlewareInterface
{

    /**
     * @var BraceApp
     *
     */
    public $app;

    /**
     * This is called on setChain();
     *
     * @param BraceApp $app
     */
    public function _setApp(BraceApp $app) {
        $this->app = $app;
    }

}
