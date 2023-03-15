<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\ORM\Query;

/**
 * @template-extends AbstractRepository<Transaction>
 */
final class TransactionRepository extends AbstractRepository
{
    protected ?string $entity = Transaction::class;
    protected ?string $alias = 't';

    public function findByHash(string $hash): ?Transaction
    {
        return $this->findOneBy(['hash' => $hash]);
    }

    public function findAllQuery(): Query
    {
        return $this->getQueryBuilder()->getQuery();
    }
}
