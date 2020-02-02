<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConfirmPassword extends Constraint
{
    /**
     * @var string
     */
    public $message = 'password.mismatch';
}
