<?php

namespace App\UseCase\Bank;

use App\Dto\Transfer\BankDto;
use App\Entity\Bank;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Validator\Validator;

final class UpdateBank
{
    public function __construct(
        private readonly Validator $validator,
        private readonly Flusher $flusher
    ) {
    }

    public function process(BankDto $dto): Bank
    {
        $this->validator->validate($dto);
        $this->validator->validate($dto->getEntity());

        $this->flusher->save();

        return $dto->getEntity();
    }
}