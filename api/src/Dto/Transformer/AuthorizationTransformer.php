<?php

namespace App\Dto\Transformer;

use App\Dto\Transfer\AuthorizationDto;
use App\Entity\Interfaces\EntityInterface;
use App\Infrastructure\Dto\AbstractDataTransformer;
use App\Infrastructure\Dto\Interfaces\DataTransferInterface;
use App\Rest\Request;

/**
 * @template-extends AbstractDataTransformer<AuthorizationDto>
 */
final class AuthorizationTransformer extends AbstractDataTransformer
{
    public function __construct(
        Request $request,
        private readonly AuthorizationDto $dto
    ) {
        parent::__construct($request);
    }

    public function transformFromRequest(?EntityInterface $entity = null): DataTransferInterface
    {
        return $this->baseTransformFromRequest($this->dto);
    }
}