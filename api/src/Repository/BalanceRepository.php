<?php

namespace App\Repository;

use App\Entity\Balance;

/**
 * @template-extends AbstractRepository<Balance>
 */
final class BalanceRepository extends AbstractRepository
{
    protected ?string $entity = Balance::class;
    protected ?string $alias = 'b';
}
