<?php

namespace App\ResponseData;

use App\Infrastructure\ResponseData\AbstractResponseData;
use App\Infrastructure\ResponseData\Constraints\Value as RDCV;

final class TransactionResponseData extends AbstractResponseData
{
    private ?string $id = null;

    #[RDCV\CallbackResponseData(PhoneResponseData::class, onlyProperties: ['id', 'number'])]
    private array $phone = [];

    private ?string $hash = null;
    private ?string $type = null;
    private array $card = [];

    #[RDCV\DateTime]
    private ?string $completedOnTime = null;
    private ?float $sum = null;

    #[RDCV\DateTime]
    private ?string $createdAt = null;
}