<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserTransformer
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function transform(User $user): User
    {
        $user = $this->setRoles($user);

        return $this->setEncodedPassword($user);
    }

    private function setRoles(User $user): User
    {
        if (\in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }

        return $user;
    }

    private function setEncodedPassword(User $user): User
    {
        $password = $user->getPassword();
        $user->setPassword($this->passwordHasher->hashPassword($user, (string) $password));

        return $user;
    }
}
