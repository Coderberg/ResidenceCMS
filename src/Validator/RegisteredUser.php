<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class RegisteredUser extends Constraint
{
    /**
     * @var string
     */
    public $message = 'user_not_found';
}
