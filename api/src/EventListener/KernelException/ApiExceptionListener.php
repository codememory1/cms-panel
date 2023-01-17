<?php

namespace App\EventListener\KernelException;

use App\Exception\HttpException;
use App\Rest\Response\Http\HttpResponseCreator;
use App\Rest\Response\Interfaces\FailedHttpResponseCollectorInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener('kernel.exception')]
final class ApiExceptionListener
{
    public function __construct(
        private readonly HttpResponseCreator $httpResponseCreator,
        private readonly FailedHttpResponseCollectorInterface $failedHttpResponseCollector
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof HttpException) {
            $this->httpException($throwable);
        }
    }

    private function httpException(HttpException $exception): void
    {
        $this->failedHttpResponseCollector->setHttpCode($exception->getHttpCode());
        $this->failedHttpResponseCollector->setMessage($exception->getMessage());
        $this->failedHttpResponseCollector->setHeaders($exception->getHeaders());

        $this->httpResponseCreator->response($this->failedHttpResponseCollector)->send();
    }
}