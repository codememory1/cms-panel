<?php

namespace App\UseCase\Role\Permission;

use App\Entity\RolePermission;
use App\Infrastructure\Doctrine\Flusher;

final class DeletePermissionToRole
{
    public function __construct(
        private readonly Flusher $flusher
    ) {
    }

    public function process(RolePermission $rolePermission): RolePermission
    {
        $this->flusher->remove($rolePermission);

        return $rolePermission;
    }
}