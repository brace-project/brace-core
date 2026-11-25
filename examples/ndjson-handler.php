<?php

use Brace\Core\BraceApp;
use Brace\Core\NdjsonStream;
use Brace\Router\Attributes\BraceRoute;
use Psr\Http\Message\ResponseInterface;

class BuildController
{
    #[BraceRoute('GET@/api/stream')]
    public function streamAction(BraceApp $braceApp): ResponseInterface
    {
        return $braceApp->responseFactory->createNdjsonResponse(function (NdjsonStream $stream): void {
            $stream->send(['type' => 'log', 'message' => 'Started']);
            sleep(1); // Replace with a step of your long-running process.
            $stream->send(['type' => 'result', 'success' => true]);
        });
    }
}

// In your application bootstrap (with RouterModule and BraceRequestLaminasModule):
// $app->router->registerClass('', BuildController::class);
