<?php

namespace Brace\Core\Base;

use Brace\Core\BraceApp;
use Brace\Core\ExceptionFormatterInterface;
use Phore\ServiceException\ServiceException;
use Phore\ServiceException\ServiceExceptionKernel;
use Psr\Http\Message\ResponseInterface;

class ServiceExceptionFormatter implements ExceptionFormatterInterface
{
    private ServiceExceptionKernel $serviceExceptionKernel;
    public function __construct(
        private BraceApp $app,
         string $serviceName,
         string $environment = 'production',
         int $defaultHttpStatusCode = 500,
         int $detailLevel = 0
    ) {
        if ( ! class_exists(ServiceExceptionKernel::class))
            throw new \InvalidArgumentException("ServiceExceptionKernel not found. Did you forget to require phore/service-exception?");
        $this->serviceExceptionKernel = new ServiceExceptionKernel($serviceName, $environment, uniqid(), $defaultHttpStatusCode, $detailLevel);
    }

    public function format(\Exception|\Error $e): ResponseInterface
    {
        $se =  $this->serviceExceptionKernel->fromThrowable($e);
        $data = $this->serviceExceptionKernel->toApiResponse($se);
        $response = $this->app->responseFactory->createResponseWithBody(json_encode($data, JSON_PRESERVE_ZERO_FRACTION|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE), $se->getCode())->withHeader("Content-Type", "application/json");;
        return $response;
    }
}
