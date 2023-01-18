<?php

namespace App\Infrastructure\Dto\Constraints;

use App\Infrastructure\Dto\AbstractDataTransferConstraintHandler;
use App\Infrastructure\Dto\Interfaces\DataTransferConstraintInterface;
use App\Infrastructure\Dto\Interfaces\DataTransferValueInterceptorConstraintHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ToEntityCallbackConstraintHandler extends AbstractDataTransferConstraintHandler implements DataTransferValueInterceptorConstraintHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    /**
     * @param ToEntityCallbackConstraint $constraint
     */
    public function handle(DataTransferConstraintInterface $constraint, mixed $value): mixed
    {
        return $this->getDataTransfer()->{$constraint->methodName}($this->em, $value);
    }
}