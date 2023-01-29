<?php

namespace App\ResponseData;

use App\Infrastructure\ResponseData\AbstractResponseData;
use App\Infrastructure\ResponseData\Constraints\Value as RDCV;

final class RoleResponseData extends AbstractResponseData
{
    private ?string $id = null;
    private ?string $title = null;

    #[RDCV\CallbackResponseData(RolePermissionResponseData::class)]
    private array $permissions = [];

    #[RDCV\DateTime]
    private ?string $createdAt = null;

    #[RDCV\DateTime]
    private ?string $updatedAt = null;
}