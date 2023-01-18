<?php

namespace App\UseCase\User;

use App\Dto\Transfer\UserDto;
use App\Entity\User;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Validator\Validator;

final class CreateUser
{
    public function __construct(
        private readonly Validator $validator,
        private readonly Flusher $flusher
    ) {
    }

    public function process(UserDto $dto): User
    {
        $this->validator->validate($dto);
        $this->validator->validate($dto->getEntity());

        $this->flusher->create($dto->getEntity());

        return $dto->getEntity();
    }
}