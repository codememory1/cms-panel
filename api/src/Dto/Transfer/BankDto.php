<?php

namespace App\Dto\Transfer;

use App\Entity\Bank;
use App\Infrastructure\Dto\AbstractDataTransfer;
use App\Infrastructure\Dto\Constraints as DtoConstraints;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @template-extends AbstractDataTransfer<Bank>
 */
final class BankDto extends AbstractDataTransfer
{
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Название банка обязательно к заполнению'),
        new Assert\Length(max: 50, maxMessage: 'Название банка не должно превышать 50 символов')
    ])]
    #[DtoConstraints\ToTypeConstraint]
    public ?string $title = null;

    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Номер банка обязательный к заполнению')
    ])]
    #[DtoConstraints\ToTypeConstraint]
    public ?int $number = null;
}