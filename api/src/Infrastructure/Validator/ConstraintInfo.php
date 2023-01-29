<?php

namespace App\Infrastructure\Validator;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\ConstraintViolationInterface;

final class ConstraintInfo
{
    public function __construct(
        private readonly ConstraintViolationInterface $constraintViolation
    ) {
    }

    public function getMessage(): ?string
    {
        return $this->constraintViolation->getMessage();
    }

    #[Pure]
    public function getPayload(): array
    {
        return $this->constraintViolation->getConstraint()->payload ?: [];
    }
}