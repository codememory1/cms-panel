<?php

namespace App\UseCase\Role;

use App\Entity\Role;
use App\Infrastructure\Doctrine\Flusher;

final class DeleteRole
{
    public function __construct(
        private readonly Flusher $flusher
    ) {
    }

    public function process(Role $role): Role
    {
        foreach ($role->getPermissions() as $permission) {
            $this->flusher->remove($permission);
        }

        $this->flusher->remove($role);

        return $role;
    }
}