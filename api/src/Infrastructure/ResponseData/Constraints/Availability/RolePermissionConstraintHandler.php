<?php

namespace App\Infrastructure\ResponseData\Constraints\Availability;

use App\Infrastructure\ResponseData\Constraints\AbstractConstraintHandler;
use App\Infrastructure\ResponseData\Interfaces\ConstraintAvailabilityHandlerInterface;
use App\Infrastructure\ResponseData\Interfaces\ConstraintInterface;

final class RolePermissionConstraintHandler extends AbstractConstraintHandler implements ConstraintAvailabilityHandlerInterface
{
    public function handle(ConstraintInterface $constraint): bool
    {
        // TODO: Логика проверки разрешения у роли авторизованного пользователя

        return false;
    }
}