<?php

namespace App\Dto\Transformer;

use App\Dto\Transfer\UserDto;
use App\Entity\Interfaces\EntityInterface;
use App\Entity\User;
use App\Infrastructure\Dto\AbstractDataTransformer;
use App\Infrastructure\Dto\Interfaces\DataTransferInterface;
use App\Rest\Request;

/**
 * @template-extends AbstractDataTransformer<UserDto>
 */
final class UserTransformer extends AbstractDataTransformer
{
    public function __construct(
        Request $request,
        private readonly UserDto $dto
    ) {
        parent::__construct($request);
    }

    public function transformFromRequest(?EntityInterface $entity = null): DataTransferInterface
    {
        return $this->baseTransformFromRequest($this->dto, $entity ?: new User());
    }
}