<?php

namespace App\UseCase\Bank;

use App\Entity\Bank;
use App\Infrastructure\Doctrine\Flusher;

final class DeleteBank
{
    public function __construct(
        private readonly Flusher $flusher
    ) {
    }

    public function process(Bank $bank): Bank
    {
        $this->flusher->remove($bank);

        return $bank;
    }
}