<?php

namespace App\Repository;

use App\Entity\Bank;

/**
 * @template-extends AbstractRepository<Bank>
 */
final class BankRepository extends AbstractRepository
{
    protected ?string $entity = Bank::class;
    protected ?string $alias = 'b';

    public function findByNumber(int $number): ?Bank
    {
        return $this->findOneBy(['number' => $number]);
    }
}
