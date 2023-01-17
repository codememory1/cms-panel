<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;
use RuntimeException;

class HttpException extends RuntimeException
{
    #[Pure]
    public function __construct(
        protected readonly int $httpCode,
        protected readonly string $text,
        protected readonly array $headers = []
    ) {
        parent::__construct($text);
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}