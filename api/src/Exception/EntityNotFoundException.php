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
}