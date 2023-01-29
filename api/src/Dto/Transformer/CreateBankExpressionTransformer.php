<?php

namespace App\Dto\Transformer;

use App\Dto\Transfer\CreateBankExpressionDto;
use App\Entity\BankExpression;
use App\Entity\Interfaces\EntityInterface;
use App\Infrastructure\Dto\AbstractDataTransformer;
use App\Infrastructure\Dto\Interfaces\DataTransferInterface;
use App\Rest\Request;

/**
 * @template-extends AbstractDataTransformer<CreateBankExpressionDto>
 */
final class CreateBankExpressionTransformer extends AbstractDataTransformer
{
    public function __construct(
        Request $request,
        private readonly CreateBankExpressionDto $dto
    ) {
        parent::__construct($request);
    }

    public function transformFromRequest(?EntityInterface $entity = null): DataTransferInterface
    {
        return $this->baseTransformFromRequest($this->dto, $entity ?: new BankExpression());
    }
}