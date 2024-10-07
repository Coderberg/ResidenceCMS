<?php

declare(strict_types=1);

namespace App\Service\Cache;

use App\Entity\User;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

trait UserDataCache
{
    /**
     * @throws InvalidArgumentException
     */
    public function setConfirmationSentAt(User $user): void
    {
        $key = $this->getKey($user);
        $cache = new FilesystemAdapter();
        $sentAt = $cache->getItem($key);
        $sentAt->set(new \DateTime('now'));
        $sentAt->expiresAfter(3600);
        $cache->save($sentAt);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getConfirmationSentAt(User $user): ?\DateTimeInterface
    {
        $key = $this->getKey($user);
        $cache = new FilesystemAdapter();
        $sentAt = $cache->getItem($key);

        return $sentAt->get();
    }

    private function getKey(User $user): string
    {
        return \sprintf('user.%s.email_confirmation_link.sent_at', $user->getId());
    }
}
