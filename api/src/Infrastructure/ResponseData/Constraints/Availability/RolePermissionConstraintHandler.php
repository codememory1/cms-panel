<?php

namespace App\Infrastructure\ResponseData\Constraints\Availability;

use App\Entity\RolePermission;
use App\Infrastructure\ResponseData\Constraints\AbstractConstraintHandler;
use App\Infrastructure\ResponseData\Interfaces\ConstraintAvailabilityHandlerInterface;
use App\Infrastructure\ResponseData\Interfaces\ConstraintInterface;
use App\Service\AuthorizedUser;

final class RolePermissionConstraintHandler extends AbstractConstraintHandler implements ConstraintAvailabilityHandlerInterface
{
    public function __construct(
        private readonly AuthorizedUser $authorizedUser
    ) {
    }

    /**
     * @param RolePermissionConstraint $constraint
     */
    public function handle(ConstraintInterface $constraint): bool
    {
        $authorizedUser = $this->authorizedUser->getUser();

        if (null === $authorizedUser) {
            return false;
        }

        return $authorizedUser
            ->getRole()
            ->getPermissions()
            ->exists(static fn(int $key, RolePermission $rolePermission) => $rolePermission->getPermission()->getKey() === $constraint->permission->name);
    }
}