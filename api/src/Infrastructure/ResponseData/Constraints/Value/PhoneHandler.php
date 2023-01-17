<?php

namespace App\Infrastructure\ResponseData\Constraints\Value;

use App\Infrastructure\ResponseData\Constraints\AbstractConstraintHandler;
use App\Infrastructure\ResponseData\Interfaces\ConstraintInterface;
use App\Infrastructure\ResponseData\Interfaces\ConstraintValueHandlerInterface;
use App\Infrastructure\ResponseData\Interfaces\ResponseDataInterface;
use function is_string;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;

final class PhoneHandler extends AbstractConstraintHandler implements ConstraintValueHandlerInterface
{
    /**
     * @param Phone                   $constraint
     * @param null|PhoneNumber|string $value
     *
     * @throws NumberParseException
     */
    public function handle(ConstraintInterface $constraint, ResponseDataInterface $responseData, mixed $value): array
    {
        if (is_string($value)) {
            $value = PhoneNumberUtil::getInstance()->parse($value);
        }

        return [
            'country_code' => $value->getCountryCode(),
            'number' => $value->getNationalNumber()
        ];
    }
}