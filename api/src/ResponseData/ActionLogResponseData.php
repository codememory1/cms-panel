<?php

namespace App\ResponseData;

use App\Infrastructure\ResponseData\AbstractResponseData;
use App\Infrastructure\ResponseData\Constraints\Value as RDCV;

final class ActionLogResponseData extends AbstractResponseData
{
    private ?string $id = null;

    #[RDCV\CallbackResponseData(UserResponseData::class, onlyProperties: ['id', 'name', 'email'])]
    private array $executor = [];
    private ?string $entity = null;
    private ?string $action = null;
    private array $payload = [];

    #[RDCV\DateTime]
    private ?string $createdAt = null;
}