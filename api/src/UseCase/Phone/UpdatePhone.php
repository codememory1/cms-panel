<?php

namespace App\UseCase\Phone;

use App\Dto\Transfer\PhoneDto;
use App\Entity\Phone;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Validator\Validator;

final class UpdatePhone
{
    public function __construct(
        private readonly Flusher $flusher,
        private readonly Validator $validator
    ) {
    }

    public function process(PhoneDto $dto): Phone
    {
        $this->validator->validate($dto);

        $this->flusher->save();

        return $dto->getEntity();
    }
}