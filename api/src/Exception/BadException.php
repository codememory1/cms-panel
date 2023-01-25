<?php

namespace App\Exception;

final class BadException extends HttpException
{
    public function __construct(string $text, array $headers = [])
    {
        parent::__construct(400, $text, $headers);
    }

    public static function badIdentifyUser(): self
    {
        return new self('Не удалось идентифицировать пользователя');
    }

    public static function badPassword(): self
    {
        return new self('Не удалось пройти аутентификацию с этим паролем');
    }

    public static function badAuthAccountBlocked(): self
    {
        return new self('Не удалось пройти аутентификацию, аккаунт заблокирован');
    }
}