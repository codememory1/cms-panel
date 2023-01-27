<?php

namespace App\Dto\Transformer;

use App\Dto\Transfer\BankDto;
use App\Entity\Bank;
use App\Entity\Interfaces\EntityInterface;
use App\Infrastructure\Dto\AbstractDataTransformer;
use App\Infrastructure\Dto\Interfaces\DataTransferInterface;
use App\Rest\Request;

/**
 * @template-extends AbstractDataTransformer<BankDto>
 */
final class BankTransformer extends AbstractDataTransformer
{
    public function __construct(
        Request $request,
        private readonly BankDto $dto
    ) {
        parent::__construct($request);
    }

    public function transformFromRequest(?EntityInterface $entity = null): DataTransferInterface
    {
        return $this->baseTransformFromRequest($this->dto, $entity ?: new Bank());
    }
}