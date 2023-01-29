<?php

namespace App\Repository;

use App\Entity\User;

/**
 * @template-extends AbstractRepository<User>
 */
final class UserRepository extends AbstractRepository
{
    protected ?string $alias = 'u';
    protected ?string $entity = User::class;

    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }
}
