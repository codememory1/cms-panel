<?php

namespace App\Infrastructure\Validator;

use App\Exception\HttpException;
use function call_user_func;
use RuntimeException;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

final class Validator
{
    public function __construct(
        private readonly SymfonyValidatorInterface $validator
    ) {
    }

    public function validate(object $object, ?callable $throw = null): void
    {
        $constraintViolationList = $this->validator->validate($object);

        foreach ($constraintViolationList as $constraintViolation) {
            $constraintViolationInfo = new ConstraintInfo($constraintViolation);

            PHP_SAPI === 'cli' ? $this->cli($constraintViolationInfo) : $this->http($constraintViolationInfo, $throw);
        }
    }

    private function cli(ConstraintInfo $constraintViolationInfo): void
    {
        throw new RuntimeException($constraintViolationInfo->getMessage());
    }

    private function http(ConstraintInfo $constraintViolationInfo, ?callable $throw = null): void
    {
        if (null === $throw) {
            throw new HttpException(400, $constraintViolationInfo->getMessage());
        }
        call_user_func($throw, $constraintViolationInfo);
    }
}