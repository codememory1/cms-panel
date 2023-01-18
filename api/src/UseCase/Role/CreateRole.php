<?php

namespace App\UseCase\Role;

use App\Dto\Transfer\CreateRoleDto;
use App\Entity\Role;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Validator\Validator;

final class CreateRole
{
    public function __construct(
        private readonly Validator $validator,
        private readonly Flusher $flusher
    ) {
    }

    public function process(CreateRoleDto $dto): Role
    {
        $this->validator->validate($dto);

        $this->flusher->create($dto->getEntity());

        return $dto->getEntity();
    }
}