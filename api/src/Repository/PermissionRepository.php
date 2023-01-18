<?php

namespace App\Repository;

use App\Entity\Permission;

/**
 * @template-extends AbstractRepository<Permission>
 */
class PermissionRepository extends AbstractRepository
{
    protected ?string $alias = 'p';
    protected ?string $entity = Permission::class;
}
