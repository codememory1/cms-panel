<?php

namespace App\UseCase\Bank\Expression;

use App\Dto\Transfer\CreateBankExpressionDto;
use App\Entity\BankExpression;
use App\Exception\ConflictException;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Validator\Validator;
use App\Repository\BankExpressionRepository;

final class CreateBankExpression
{
    public function __construct(
        private readonly Validator $validator,
        private readonly BankExpressionRepository $bankExpressionRepository,
        private readonly Flusher $flusher
    ) {
    }

    public function process(CreateBankExpressionDto $dto): BankExpression
    {
        $this->validator->validate($dto);

        if (null !== $this->bankExpressionRepository->findOneBy(['bank' => $dto->bank])) {
            throw ConflictException::expressionForBankExist();
        }

        $this->flusher->create($dto->getEntity());

        return $dto->getEntity();
    }
}