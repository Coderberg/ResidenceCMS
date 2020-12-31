<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$fullName, $username, $phone, $email, $roles]) {
            $user = new User();
            $user->setFullName($fullName);
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user, $username
            ));
            $user->setPhone($phone);
            $user->setEmail($email);
            $user->setRoles($roles);
            $manager->persist($user);
            $this->addReference($username, $user);
        }
        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $cityData = [$fullName, $username, $phone, $email, $roles];
            ['Пламен Николов', 'admin', '+359892473515', 'plamen.nikolov89@gmail.com', ['ROLE_ADMIN', 'ROLE_USER']],
            ['Тестов потребител', 'user', '+359892473515', 'user@user.com', ['ROLE_USER']],
        ];
    }
}
