<?php

namespace App\Dto\Transfer;

use App\Entity\Permission;
use App\Entity\Role;
use App\Exception\EntityNotFoundException;
use App\Infrastructure\Dto\AbstractDataTransfer;
use App\Infrastructure\Dto\Constraints as DtoConstraints;
use App\Repository\PermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use function is_string;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @template-extends AbstractDataTransfer<Role>
 */
final class RoleDto extends AbstractDataTransfer
{
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Название обязательно к заполнению'),
        new Assert\Length(max: 255, maxMessage: 'Название роли не должно превышать 255 символов')
    ])]
    #[DtoConstraints\ToTypeConstraint]
    public ?string $title = null;

    #[DtoConstraints\ToTypeConstraint]
    #[DtoConstraints\ToEntityCallbackConstraint('permissionsCallback')]
    public array $permissions = [];

    public function permissionsCallback(EntityManagerInterface $em, array $value): array
    {
        /** @var PermissionRepository $permissionRepository */
        $permissionRepository = $em->getRepository(Permission::class);
        $permissions = [];

        foreach (array_unique($value) as $permissionKey) {
            if (is_string($permissionKey)) {
                if (null === $permission = $permissionRepository->findByKey($permissionKey)) {
                    throw EntityNotFoundException::permission($permissionKey);
                }

                $permissions[] = $permission;
            }
        }

        return $permissions;
    }
}