<?php

namespace App\Infrastructure\ResponseData\Builder;

use JetBrains\PhpStorm\Pure;
use ReflectionProperty;
use function Symfony\Component\String\u;

final class AllowedPropertyBuilder
{
    public function __construct(
        public readonly ReflectionProperty $reflectionProperty
    ) {
    }

    #[Pure]
    public function getPropertyName(): string
    {
        return $this->reflectionProperty->getName();
    }

    public function getPropertyNameInResponse(): string
    {
        return u($this->getPropertyName())->snake()->toString();
    }
}