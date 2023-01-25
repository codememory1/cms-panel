<?php

namespace App\Validator\Constraints;

use Exception;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class PhoneValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Phone) {
            throw new UnexpectedTypeException($constraint, Phone::class);
        }

        if ($constraint->allowedNull || null !== $value) {
            try {
                PhoneNumberUtil::getInstance()->parse($value);
            } catch (Exception) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ phone }}', $value)
                    ->addViolation();
            }
        } else {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ phone }}', '_')
                ->addViolation();
        }
    }
}