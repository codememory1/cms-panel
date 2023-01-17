<?php

namespace App\Attribute;

use App\Infrastructure\ControllerAttribute\Interfaces\ControllerAttributeHandlerInterface;
use App\Infrastructure\ControllerAttribute\Interfaces\ControllerAttributeInterface;

final class AuthorizationHandler implements ControllerAttributeHandlerInterface
{
    public function handle(ControllerAttributeInterface $annotation): void
    {
        // TODO: Проверка на авторизацию, если ее нет выбрасывать исключение
        // TODO: Проверка на разрешения в роли, передается массив, что означает || если массив пустой, то проверка просто на авторизацию
    }
}