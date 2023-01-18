<?php

namespace App\Dto\Transfer;

use App\Entity\Role;
use App\Infrastructure\Dto\AbstractDataTransfer;
use App\Infrastructure\Dto\Constraints as DtoConstraints;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @template-extends AbstractDataTransfer<Role>
 */
final class UpdateRoleDto extends AbstractDataTransfer
{
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Название обязательно к заполнению'),
        new Assert\Length(max: 255, maxMessage: 'Название роли не должно превышать 255 символов')
    ])]
    #[DtoConstraints\ToTypeConstraint]
    public ?string $title = null;
}