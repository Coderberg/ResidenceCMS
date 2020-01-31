<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\User;

final class SendResetPasswordLink
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
