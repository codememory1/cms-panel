<?php

namespace App\ResponseData;

use App\Infrastructure\ResponseData\AbstractResponseData;

final class BalanceResponseData extends AbstractResponseData
{
    private ?float $balance = null;
}