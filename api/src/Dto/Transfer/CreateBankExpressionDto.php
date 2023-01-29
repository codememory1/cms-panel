<?php

namespace App\Dto\Transfer;

use App\Entity\Bank;
use App\Infrastructure\Dto\Constraints as DtoConstraints;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateBankExpressionDto extends BankExpressionDto
{
    #[DtoConstraints\ToEntityConstraint('id')]
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Банк обязательный к заполнению или указанный банк не существует')
    ])]
    public ?Bank $bank = null;
}