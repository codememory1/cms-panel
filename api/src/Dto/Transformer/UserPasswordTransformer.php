<?php

namespace App\Dto\Transformer;

use App\Dto\Transfer\UserPasswordDto;
use App\Entity\Interfaces\EntityInterface;
use App\Infrastructure\Dto\AbstractDataTransformer;
use App\Infrastructure\Dto\Interfaces\DataTransferInterface;
use App\Rest\Request;

/**
 * @template-extends AbstractDataTransformer<UserPasswordDto>
 */
final class UserPasswordTransformer extends AbstractDataTransformer
{
    public function __construct(
        Request $request,
        private readonly UserPasswordDto $dto
    ) {
        parent::__construct($request);
    }

    public function transformFromRequest(?EntityInterface $entity = null): DataTransferInterface
    {
        return $this->baseTransformFromRequest($this->dto, $entity);
    }
}