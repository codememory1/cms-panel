<?php

namespace App\Package\Jwt;

use DateTimeImmutable;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use JetBrains\PhpStorm\ArrayShape;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class AccessGenerator
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly string $pathToAccessJwtPublicSecret,
        private readonly string $pathToAccessJwtPrivateSecret,
        private readonly int $jwtAccessTtl
    ) {
    }

    public function encode(array $data): string
    {
        return JWT::encode(
            $this->collectPayload($data),
            $this->getSecretKey($this->pathToAccessJwtPrivateSecret),
            'RS256'
        );
    }

    public function decode(string $token): object|bool
    {
        try {
            return JWT::decode($token, new Key($this->getSecretKey($this->pathToAccessJwtPublicSecret), 'RS256'));
        } catch (Exception) {
            return false;
        }
    }

    public function getSecretKey(string $pathToSecret): string
    {
        $kernelProjectDir = $this->parameterBag->get('kernel.project_dir');

        return file_get_contents("{$kernelProjectDir}/{$pathToSecret}");
    }

    private function generateSub(): string
    {
        return Uuid::uuid4()->toString();
    }

    #[ArrayShape([
        'sub' => 'string',
        'exp' => 'int',
        'iat' => 'int',
        2 => 'array'
    ])]
    private function collectPayload(array $payload): array
    {
        $dateTime = new DateTimeImmutable();

        return [
            'sub' => $this->generateSub(),
            'exp' => $dateTime->getTimestamp() + $this->jwtAccessTtl,
            'iat' => $dateTime->getTimestamp(),
            ...$payload
        ];
    }
}