<?php

namespace App\Infrastructure\ResponseData\Constraints\Value;

use App\Infrastructure\ResponseData\Interfaces\ConstraintInterface;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class CustomCallback implements ConstraintInterface
{
    public function __construct(
        public readonly string $methodName,
        public readonly ?string $class = null
    ) {
    }

    public function getHandler(): string
    {
        return CustomCallbackHandler::class;
    }
}