<?php

namespace App\Dto\Transfer;

use App\Infrastructure\Dto\AbstractDataTransfer;
use App\Infrastructure\Dto\Constraints as DtoConstraints;
use Symfony\Component\Validator\Constraints as Assert;

final class AuthorizationDto extends AbstractDataTransfer
{
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'E-mail обязателен к заполнению')
    ])]
    #[DtoConstraints\ToTypeConstraint]
    public ?string $email = null;

    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Пароль обязателен к заполнению')
    ])]
    #[DtoConstraints\ToTypeConstraint]
    public ?string $password = null;
}