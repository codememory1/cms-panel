<?php

namespace App\Dto\Transfer;

use App\Entity\BankExpression;
use App\Infrastructure\Dto\AbstractDataTransfer;
use App\Infrastructure\Dto\Constraints as DtoConstraints;

/**
 * @template-extends AbstractDataTransfer<BankExpression>
 */
final class BankExpressionDto extends AbstractDataTransfer
{
    #[DtoConstraints\ToTypeConstraint]
    public array $transfer = [];

    #[DtoConstraints\ToTypeConstraint]
    public array $enrollment = [];

    #[DtoConstraints\ToTypeConstraint]
    public array $payment = [];

    #[DtoConstraints\ToTypeConstraint]
    public array $purchase = [];
}