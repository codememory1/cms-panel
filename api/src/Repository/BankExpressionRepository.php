<?php

namespace App\Repository;

use App\Entity\BankExpression;

/**
 * @template-extends AbstractRepository<BankExpression>
 */
final class BankExpressionRepository extends AbstractRepository
{
    protected ?string $entity = BankExpression::class;
    protected ?string $alias = 'be';
}
