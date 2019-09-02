<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public const ADMIN_USER_REFERENCE = 'admin';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFullName('John Smith');
        $user->setUsername('admin');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user, 'admin'
        ));
        $user->setPhone('0(0)99766899');
        $user->setEmail('admin@admin');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
        $this->addReference(self::ADMIN_USER_REFERENCE, $user);
    }
}
