<?php

namespace App\Infrastructure\ResponseData\Builder;

use App\Infrastructure\ResponseData\Interfaces\ConstraintInterface;

final class PropertyInterceptorBuilder
{
    public function __construct(
        public readonly ConstraintInterface $constraint,
        public readonly string $handler
    ) {
    }
}