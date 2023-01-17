<?php

namespace App\Attribute;

use App\Enum\RolePermissionEnum;
use App\Infrastructure\ControllerAttribute\Interfaces\ControllerAttributeInterface;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Authorization implements ControllerAttributeInterface
{
    /**
     * @param array<int, RolePermissionEnum> $expectedPermissions
     */
    public function __construct(
        public array $expectedPermissions = []
    ) {
    }

    public function getHandler(): string
    {
        return AuthorizationHandler::class;
    }
}