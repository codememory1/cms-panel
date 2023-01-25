<?php

namespace App\Infrastructure\ControllerAttribute\Interfaces;

interface ControllerAttributeInterface
{
    public function getHandler(): string;
}