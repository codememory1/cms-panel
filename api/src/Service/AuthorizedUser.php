<?php

namespace App\Service;

use App\Entity\User;
use App\Package\Jwt\AccessGenerator;
use App\Repository\UserRepository;
use App\Rest\Request;

final class AuthorizedUser
{
    private ?User $user = null;

    public function __construct(
        private readonly Request $request,
        private readonly AccessGenerator $jwtAccessGenerator,
        private readonly UserRepository $userRepository
    ) {
    }

    public function getToken(): ?string
    {
        $request = $this->request->getRequest();
        $authorizationHeader = $request?->headers->get('Authorization');
        $authorizationHeaderData = null === $authorizationHeader ? [] : explode(' ', $authorizationHeader, 2);

        if (count($authorizationHeaderData) > 1 && 'Bearer' === $authorizationHeaderData[0]) {
            return $authorizationHeaderData[1];
        }

        return null;
    }

    public function getTokenData(): array|bool
    {
        $token = $this->getToken();

        if (null === $token) {
            return false;
        }

        if (false !== $tokenData = $this->jwtAccessGenerator->decode($token)) {
            return (array) $tokenData;
        }

        return false;
    }

    public function getUser(): ?User
    {
        if (null !== $this->user) {
            return $this->user;
        }

        if (false !== $tokenData = $this->getTokenData()) {
            return $this->user = $this->userRepository->find($tokenData['id']);
        }

        return null;
    }
}