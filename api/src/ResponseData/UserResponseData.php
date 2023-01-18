<?php

namespace App\ResponseData;

use App\Infrastructure\ResponseData\AbstractResponseData;
use App\Infrastructure\ResponseData\Constraints\Value as RDCV;

final class UserResponseData extends AbstractResponseData
{
    private ?string $id = null;
    private ?string $name = null;
    private ?string $email = null;
    private ?string $status = null;
    private ?string $roleName = null;

    #[RDCV\DateTime]
    private ?string $createdAt = null;

    #[RDCV\DateTime]
    private ?string $updatedAt = null;
}