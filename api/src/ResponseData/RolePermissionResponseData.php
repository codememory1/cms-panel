<?php

namespace App\ResponseData;

use App\Infrastructure\ResponseData\AbstractResponseData;
use App\Infrastructure\ResponseData\Constraints\Value as RDCV;

final class RolePermissionResponseData extends AbstractResponseData
{
    private ?string $id = null;

    #[RDCV\CallbackResponseData(PermissionResponseData::class)]
    private array $permission = [];

    #[RDCV\DateTime]
    private ?string $createdAt = null;

    #[RDCV\DateTime]
    private ?string $updatedAt = null;
}