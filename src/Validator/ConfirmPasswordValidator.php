<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class ConfirmPasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        /* @var $constraint ConfirmPassword */

        if (null === $value || '' === $value) {
            return;
        }

        $password = $this->context->getRoot()->get('password')->getData();

        if ($password !== $value) {
            $this->context->buildViolation($constraint->message)
                ->atPath('password_confirmation')
                ->addViolation();
        }
    }
}
