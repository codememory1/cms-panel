<?php

namespace App\Security\Authorization;

use App\Dto\Transfer\AuthorizationDto;
use App\Entity\User;
use App\Exception\BadException;
use App\Package\Hashing\Password;

final class Authentication
{
    public function __construct(
        private readonly Password $passwordHashing
    ) {
    }

    public function authenticate(User $identifiedUser, AuthorizationDto $dto): void
    {
        if (!$this->passwordHashing->compare($dto->password, $identifiedUser->getPassword())) {
            throw BadException::badPassword();
        }
    }
}