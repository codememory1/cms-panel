<?php

namespace App\Infrastructure\ResponseData\Constraints\Availability;

use App\Enum\RolePermissionEnum;
use App\Infrastructure\ResponseData\Interfaces\ConstraintInterface;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class RolePermissionConstraint implements ConstraintInterface
{
    public function __construct(
        public readonly RolePermissionEnum $rolePermission
    ) {
    }

    public function getHandler(): string
    {
        return RolePermissionConstraintHandler::class;
    }
}