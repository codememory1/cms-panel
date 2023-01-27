<?php

namespace App\ResponseData;

use App\Infrastructure\ResponseData\AbstractResponseData;
use App\Infrastructure\ResponseData\Constraints\Value as RDCV;

final class BankExpressionResponseData extends AbstractResponseData
{
    #[RDCV\CallbackResponseData(BankResponseData::class, onlyProperties: ['id', 'title'])]
    private array $bank = [];

    private array $transfer = [];
    private array $enrollment = [];
    private array $payment = [];
    private array $purchase = [];

    #[RDCV\DateTime]
    private ?string $createdAt = null;

    #[RDCV\DateTime]
    private ?string $updatedAt = null;
}