<?php

namespace App\Dto\Transfer;

use App\Entity\Permission;
use App\Entity\RolePermission;
use App\Infrastructure\Dto\AbstractDataTransfer;
use App\Infrastructure\Dto\Constraints as DtoConstraints;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @template-extends AbstractDataTransfer<RolePermission>
 */
final class RolePermissionDto extends AbstractDataTransfer
{
    #[DtoConstraints\ToEntityConstraint('key')]
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Разрешение обязательно к заполнению или введенное разрешение не существует')
    ])]
    public ?Permission $permission = null;
}