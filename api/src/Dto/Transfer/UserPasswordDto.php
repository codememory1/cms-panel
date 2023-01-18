<?php

namespace App\Dto\Transfer;

use App\Entity\User;
use App\Infrastructure\Dto\AbstractDataTransfer;
use App\Infrastructure\Dto\Constraints as DtoConstraints;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @template-extends AbstractDataTransfer<User>
 */
final class UserPasswordDto extends AbstractDataTransfer
{
    #[DtoConstraints\ToTypeConstraint]
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Пароль обязательный к заполнению'),
        new Assert\Length(min: 8, minMessage: 'Пароль должен содержать минимум 8 символов'),
    ])]
    public ?string $password = null;
}