<?php

declare(strict_types=1);

namespace App\Tests\Integration\Transformer;

use App\Entity\User;
use App\Transformer\UserTransformer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserTransformerTest extends KernelTestCase
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
        self::bootKernel();
        $container = static::getContainer();

        return [
            $container->get(UserTransformer::class),
            $container->get(UserPasswordHasherInterface::class),
        ];
    }
}
