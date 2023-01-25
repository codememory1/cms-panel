<?php

namespace App\UseCase\Phone;

use App\Entity\Phone;
use App\Infrastructure\Doctrine\Flusher;

final class DeletePhone
{
    public function __construct(
        private readonly Flusher $flusher
    ) {
    }

    public function process(Phone $phone): Phone
    {
        $this->flusher->remove($phone);

        return $phone;
    }
}