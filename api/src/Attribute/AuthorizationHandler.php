<?php

namespace App\Attribute;

use App\Entity\RolePermission;
use App\Exception\HttpException;
use App\Infrastructure\ControllerAttribute\Interfaces\ControllerAttributeHandlerInterface;
use App\Infrastructure\ControllerAttribute\Interfaces\ControllerAttributeInterface;
use App\Service\AuthorizedUser;

final class AuthorizationHandler implements ControllerAttributeHandlerInterface
{
    public function __construct(
        private readonly AuthorizedUser $authorizedUser
    ) {
    }

    /**
     * @param Authorization $attribute
     */
    public function handle(ControllerAttributeInterface $attribute): void
    {
        $authorizedUser = $this->authorizedUser->getUser();

        if (null === $authorizedUser) {
            throw new HttpException(401, 'Для выполнения данного действия, требуется авторизация');
        }

        if (null !== $attribute->expectedPermission) {
            $userRoleHasPermission = $authorizedUser
                ->getRole()
                ->getPermissions()
                ->exists(static fn(int $key, RolePermission $rolePermission) => $rolePermission->getPermission()->getKey() === $attribute->expectedPermission->name);

            if (!$userRoleHasPermission) {
                throw new HttpException(403, 'Недостаточно прав для выполнения данного действия');
            }
        }
    }
}