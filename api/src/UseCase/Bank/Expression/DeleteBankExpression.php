<?php

namespace App\UseCase\Bank\Expression;

use App\Entity\BankExpression;
use App\Infrastructure\Doctrine\Flusher;

final class DeleteBankExpression
{
    public function __construct(
        private readonly Flusher $flusher
    ) {
    }

    public function process(BankExpression $bankExpression): BankExpression
    {
        $this->flusher->remove($bankExpression);

        return $bankExpression;
    }
}