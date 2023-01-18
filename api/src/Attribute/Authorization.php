<?php

namespace App\Attribute;

use App\Enum\PermissionEnum;
use App\Infrastructure\ControllerAttribute\Interfaces\ControllerAttributeInterface;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Authorization implements ControllerAttributeInterface
{
    public function __construct(
        public ?PermissionEnum $expectedPermission = null
    ) {
    }

    public function getHandler(): string
    {
        return AuthorizationHandler::class;
    }
}