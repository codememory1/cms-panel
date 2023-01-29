<?php

namespace App\Rest\Response\Interfaces;

interface ResponseCollectorInterface
{
    public function getHttpCode(): int;

    public function setHttpCode(int $code): self;

    public function collect(): self;

    public function getCollectedResponse(): array;
}