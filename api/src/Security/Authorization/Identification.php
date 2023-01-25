<?php

namespace App\Security\Authorization;

use App\Dto\Transfer\AuthorizationDto;
use App\Entity\User;
use App\Exception\BadException;
use App\Repository\UserRepository;

final class Identification
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function identify(AuthorizationDto $dto): User
    {
        if (null === $identifiedUser = $this->userRepository->findByEmail($dto->email)) {
            throw BadException::badIdentifyUser();
        }

        return $identifiedUser;
    }
}