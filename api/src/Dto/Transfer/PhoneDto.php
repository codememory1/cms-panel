<?php

namespace App\Dto\Transfer;

use App\Entity\Phone;
use App\Infrastructure\Dto\AbstractDataTransfer;
use App\Infrastructure\Dto\Constraints as DtoConstraints;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @template-extends AbstractDataTransfer<Phone>
 */
final class PhoneDto extends AbstractDataTransfer
{
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Номер телефона обязательный к заполнению'),
        new AppAssert\Phone(message: 'Некорректный телефон, проверьте чтоб был указан регион')
    ])]
    #[DtoConstraints\ToTypeConstraint]
    public ?string $number = null;
}