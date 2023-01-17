<?php

namespace App\Infrastructure\ControllerAttribute\Interfaces;

interface ControllerAttributeHandlerInterface
{
    public function handle(ControllerAttributeInterface $annotation): void;
}