<?php

namespace App\Security\Authorization;

use App\Dto\Transfer\AuthorizationDto;
use App\Infrastructure\Validator\Validator;
use App\Package\Jwt\AccessGenerator;

final class Authorization
{
    public function __construct(
        private readonly Validator $validator,
        private readonly Identification $identification,
        private readonly Authentication $authentication,
        private readonly AccessGenerator $jwtAccessGenerator
    ) {
    }

    public function authorize(AuthorizationDto $dto): string
    {
        $this->validator->validate($dto);

        $identifiedUser = $this->identification->identify($dto);

        $this->authentication->authenticate($identifiedUser, $dto);

        return $this->jwtAccessGenerator->encode(['id' => $identifiedUser->getId()]);
    }
}