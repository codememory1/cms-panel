<?php

namespace App\UseCase\Role;

use App\Dto\Transfer\UpdateRoleDto;
use App\Entity\Role;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Validator\Validator;

final class UpdateRole
{
    public function __construct(
        private readonly Validator $validator,
        private readonly Flusher $flusher
    ) {
    }

    public function process(UpdateRoleDto $dto): Role
    {
        $this->validator->validate($dto);

        $this->flusher->save();

        return $dto->getEntity();
    }
}