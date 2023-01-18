<?php

namespace App\UseCase\Role\Permission;

use App\Dto\Transfer\RolePermissionDto;
use App\Entity\Role;
use App\Entity\RolePermission;
use App\Exception\ConflictException;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Validator\Validator;

final class AddPermissionToRole
{
    public function __construct(
        private readonly Validator $validator,
        private readonly Flusher $flusher
    ) {
    }

    public function process(Role $role, RolePermissionDto $dto): RolePermission
    {
        $this->validator->validate($dto);

        if ($role->hasPermission($dto->permission)) {
            throw ConflictException::roleItHasPermission($dto->permission->getKey());
        }

        $role->addPermission($dto->getEntity());

        $this->flusher->save();

        return $dto->getEntity();
    }
}