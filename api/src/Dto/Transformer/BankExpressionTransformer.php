<?php

namespace App\Dto\Transformer;

use App\Dto\Transfer\BankExpressionDto;
use App\Entity\BankExpression;
use App\Entity\Interfaces\EntityInterface;
use App\Infrastructure\Dto\AbstractDataTransformer;
use App\Infrastructure\Dto\Interfaces\DataTransferInterface;
use App\Rest\Request;

/**
 * @template-extends AbstractDataTransformer<BankExpressionDto>
 */
final class BankExpressionTransformer extends AbstractDataTransformer
{
    public function __construct(
        Request $request,
        private readonly BankExpressionDto $dto
    ) {
        parent::__construct($request);
    }

    public function transformFromRequest(?EntityInterface $entity = null): DataTransferInterface
    {
        return $this->baseTransformFromRequest($this->dto, $entity ?: new BankExpression());
    }
}