<?php

namespace App\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Exception;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use LogicException;

final class PhoneType extends StringType
{
    public const NAME = 'phone';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = 20;
        $column['fixed'] = true;

        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @inheritDoc
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        try {
            PhoneNumberUtil::getInstance()->parse($value);

            return $value;
        } catch (Exception) {
            throw new LogicException("Phone \"{$value}\" is not correct");
        }
    }

    /**
     * @throws NumberParseException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?PhoneNumber
    {
        if (null === $value) {
            return null;
        }

        return PhoneNumberUtil::getInstance()->parse($value);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}