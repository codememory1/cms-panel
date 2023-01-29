<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Phone extends Constraint
{
    public function __construct(
        public readonly string $message = 'Incorrect phone format {{ phone }}',
        public readonly bool $allowedNull = false,
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
    }
}