<?php

namespace App\Dto\Transformer;

use App\Dto\Transfer\RolePermissionDto;
use App\Entity\Interfaces\EntityInterface;
use App\Entity\RolePermission;
use App\Infrastructure\Dto\AbstractDataTransformer;
use App\Infrastructure\Dto\Interfaces\DataTransferInterface;
use App\Rest\Request;

/**
 * @template-extends AbstractDataTransformer<RolePermissionDto>
 */
final class RolePermissionTransformer extends AbstractDataTransformer
{
    public function __construct(
        Request $request,
        private readonly RolePermissionDto $dto
    ) {
        parent::__construct($request);
    }

    public function transformFromRequest(?EntityInterface $entity = null): DataTransferInterface
    {
        return $this->baseTransformFromRequest($this->dto, $entity ?: new RolePermission());
    }
}