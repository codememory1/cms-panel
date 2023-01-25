<?php

namespace App\Infrastructure\Dto\Builder;

use App\Infrastructure\Dto\Interfaces\DataTransferConstraintInterface;
use ReflectionAttribute;

final class DtoConstraintBuilder
{
    public function __construct(
        public readonly DataTransferConstraintInterface $constraint,
        public readonly ReflectionAttribute $reflectionAttribute
    ) {
    }
}