<?php

namespace App\Exception;

final class EntityNotFoundException extends HttpException
{
    public function __construct(string $text, array $headers = [])
    {
        parent::__construct(404, $text, $headers);
    }

    public static function page(): self
    {
        return new self('По данному запросу страницы не существует');
    }

    public static function phone(): self
    {
        return new self('Номер телефона по данному идентификатору не существует');
    }

    public static function permission(string $key): self
    {
        return new self("Разрешение с ключем \"{$key}\" не существует");
    }

    public static function rolePermission(): self
    {
        return new self('Разрешение роли по данному идентификатору не существует');
    }

    public static function role(): self
    {
        return new self('Роль по данному идентификатору не существует');
    }

    public static function user(): self
    {
        return new self('Пользователь по данному идентификатору не существует');
    }

    public static function bank(): self
    {
        return new self('Банк по данному идентификатору не существует');
    }
}