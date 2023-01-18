<?php

namespace App\Dto\Transfer;

use App\Entity\Role;
use App\Entity\User;
use App\Enum\UserStatusEnum;
use App\Infrastructure\Dto\AbstractDataTransfer;
use App\Infrastructure\Dto\Constraints as DtoConstraints;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @template-extends AbstractDataTransfer<User>
 */
final class UserDto extends AbstractDataTransfer
{
    #[DtoConstraints\ToTypeConstraint]
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Имя пользователя обязательно к заполнению'),
        new Assert\Length(max: 255, maxMessage: 'Имя пользователя не может превышать 255 символов')
    ])]
    public ?string $name = null;

    #[DtoConstraints\ToTypeConstraint]
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'E-mail обязательный к заполнению'),
        new Assert\Email(message: 'Некорректный формат E-mail'),
    ])]
    public ?string $email = null;

    #[DtoConstraints\ToEnumConstraint(UserStatusEnum::class)]
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Статус обязательный к заполнению или введен некорректный статус')
    ])]
    public ?UserStatusEnum $status = null;

    #[DtoConstraints\ToEntityConstraint('id')]
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Роль обязательна к заполнению или введена некорректная роль')
    ])]
    public ?Role $role = null;
}