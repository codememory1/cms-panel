<?php

namespace App\Dto\Transformer;

use App\Dto\Transfer\CreateRoleDto;
use App\Entity\Interfaces\EntityInterface;
use App\Entity\Role;
use App\Infrastructure\Dto\AbstractDataTransformer;
use App\Infrastructure\Dto\Interfaces\DataTransferInterface;
use App\Rest\Request;

/**
 * @template-extends AbstractDataTransformer<CreateRoleDto>
 */
final class CreateRoleTransformer extends AbstractDataTransformer
{
    public function __construct(
        Request $request,
        private readonly CreateRoleDto $dto
    ) {
        parent::__construct($request);
    }

    public function transformFromRequest(?EntityInterface $entity = null): DataTransferInterface
    {
        return $this->baseTransformFromRequest($this->dto, $entity ?: new Role());
    }
}