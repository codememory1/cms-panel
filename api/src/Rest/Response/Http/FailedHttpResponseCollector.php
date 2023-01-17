<?php

namespace App\Rest\Response\Http;

use App\Rest\Response\AbstractHttpResponseCollector;
use App\Rest\Response\Interfaces\FailedHttpResponseCollectorInterface;
use App\Rest\Response\Interfaces\HttpResponseCollectorInterface;
use LogicException;

final class FailedHttpResponseCollector extends AbstractHttpResponseCollector implements FailedHttpResponseCollectorInterface
{
    private array $response = [];
    private ?string $message = null;

    public function collect(): HttpResponseCollectorInterface
    {
        $this->response = ['error' => $this->getMessage()];

        return $this;
    }

    public function getCollectedResponse(): array
    {
        return $this->response;
    }

    public function getMessage(): string
    {
        if (null === $this->message) {
            throw new LogicException('No message specified for failed http response');
        }

        return $this->message;
    }

    public function setMessage(string $message): FailedHttpResponseCollectorInterface
    {
        $this->message = $message;

        return $this;
    }
}