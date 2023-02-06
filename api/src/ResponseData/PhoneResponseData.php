<?php

namespace App\ResponseData;

use App\Infrastructure\ResponseData\AbstractResponseData;
use App\Infrastructure\ResponseData\Constraints\Value as RDCV;

final class PhoneResponseData extends AbstractResponseData
{
    private ?string $id = null;

    #[RDCV\Phone]
    private array $number = [];

    #[RDCV\CallbackResponseData(BalanceResponseData::class)]
    private array $balance = [];
    private ?string $status = null;

    #[RDCV\DateTime]
    private ?string $createdAt = null;

    #[RDCV\DateTime]
    private ?string $updatedAt = null;
}