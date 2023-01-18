<?php

namespace App\UseCase\User;

use App\Dto\Transfer\UserPasswordDto;
use App\Entity\User;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Validator\Validator;

final class UpdateUserPassword
{
    public function __construct(
        private readonly Validator $validator,
        private readonly Flusher $flusher
    ) {
    }

    public function process(UserPasswordDto $dto): User
    {
        $this->validator->validate($dto);

        $this->flusher->save();

        return $dto->getEntity();
    }
}