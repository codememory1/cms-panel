<?php

namespace App\ResponseData;

use App\Infrastructure\ResponseData\AbstractResponseData;
use App\Infrastructure\ResponseData\Constraints\Value as RDCV;

final class TransactionResponseData extends AbstractResponseData
{
    private ?string $id = null;
    private ?string $hash = null;
    private ?string $type = null;
    private array $card = [];
    private ?string $completedOnTime = null;
    private ?float $sum = null;

    #[RDCV\DateTime]
    private ?string $createdAt = null;
}