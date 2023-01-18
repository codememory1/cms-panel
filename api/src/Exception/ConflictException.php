<?php

namespace App\Exception;

final class ConflictException extends HttpException
{
    public function __construct(string $text, array $headers = [])
    {
        parent::__construct(409, $text, $headers);
    }

    public static function roleItHasPermission(string $permission): self
    {
        return new self("Данная роль уже имеет разрешение \"{$permission}\"");
    }
}