<?php

namespace App\Repository;

use App\Entity\Role;

/**
 * @template-extends AbstractRepository<Role>
 */
class RoleRepository extends AbstractRepository
{
    protected ?string $alias = 'r';
    protected ?string $entity = Role::class;
}
