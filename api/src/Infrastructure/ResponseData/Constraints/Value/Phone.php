<?php

namespace App\Infrastructure\ResponseData\Constraints\Value;

use App\Infrastructure\ResponseData\Interfaces\ConstraintInterface;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Phone implements ConstraintInterface
{
    public function getHandler(): string
    {
        return PhoneHandler::class;
    }
}