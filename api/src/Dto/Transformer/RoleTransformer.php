<?php

namespace App\Dto\Transformer;

use App\Dto\Transfer\RoleDto;
use App\Entity\Interfaces\EntityInterface;
use App\Entity\Role;
use App\Infrastructure\Dto\AbstractDataTransformer;
use App\Infrastructure\Dto\Interfaces\DataTransferInterface;
use App\Rest\Request;

/**
 * @template-extends AbstractDataTransformer<RoleDto>
 */
final class RoleTransformer extends AbstractDataTransformer
{
    public function __construct(
        Request $request,
        private readonly RoleDto $dto
    ) {
        parent::__construct($request);
    }

    public function transformFromRequest(?EntityInterface $entity = null): DataTransferInterface
    {
        return $this->baseTransformFromRequest($this->dto, $entity ?: new Role());
    }
}