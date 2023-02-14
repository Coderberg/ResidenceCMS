<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\User;

final class SendEmailConfirmationLink
{
    public function __construct(private readonly User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
