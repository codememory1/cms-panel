<?php

namespace App\Dto\Transformer;

use App\Dto\Transfer\PhoneDto;
use App\Entity\Interfaces\EntityInterface;
use App\Entity\Phone;
use App\Infrastructure\Dto\AbstractDataTransformer;
use App\Infrastructure\Dto\Interfaces\DataTransferInterface;
use App\Rest\Request;

/**
 * @template-extends AbstractDataTransformer<PhoneDto>
 */
final class PhoneTransformer extends AbstractDataTransformer
{
    public function __construct(
        Request $request,
        private readonly PhoneDto $dto
    ) {
        parent::__construct($request);
    }

    public function transformFromRequest(?EntityInterface $entity = null): DataTransferInterface
    {
        return $this->baseTransformFromRequest($this->dto, $entity ?: new Phone());
    }
}