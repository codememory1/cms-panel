<?php

namespace App\UseCase\Bank\Expression;

use App\Dto\Transfer\BankExpressionDto;
use App\Entity\BankExpression;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Validator\Validator;

final class UpdateBankExpression
{
    public function __construct(
        private readonly Validator $validator,
        private readonly Flusher $flusher
    ) {
    }

    public function process(BankExpressionDto $dto): BankExpression
    {
        $this->validator->validate($dto);

        $this->flusher->save();

        return $dto->getEntity();
    }
}