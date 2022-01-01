<?php

declare(strict_types=1);

namespace App\Tests\Transformer;

use App\Entity\User;
use App\Transformer\UserTransformer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTransformerTest extends WebTestCase
{
    private const TEST_PASSWORD = 'My.Test@password';

    public function testTransform(): void
    {
        [$transformer, $passwordHasher] = $this->getDependencies();

        $user = new User();
        $user->setPassword(self::TEST_PASSWORD);
        $this->assertSame(self::TEST_PASSWORD, $user->getPassword());

        $transformer->transform($user);
        $this->assertNotSame(self::TEST_PASSWORD, $user->getPassword());
        $this->assertTrue($passwordHasher->isPasswordValid($user, self::TEST_PASSWORD));
    }

    private function getDependencies(): array
    {
        $container = static::createClient()->getContainer();

        return [
            $container->get(UserTransformer::class),
            $container->get(UserPasswordHasherInterface::class),
        ];
    }
}
