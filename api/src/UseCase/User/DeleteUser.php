<?php

namespace App\UseCase\User;

use App\Entity\User;
use App\Infrastructure\Doctrine\Flusher;

final class DeleteUser
{
    public function __construct(
        private readonly Flusher $flusher
    ) {
    }

    public function process(User $user): User
    {
        $this->flusher->remove($user);

        return $user;
    }
}