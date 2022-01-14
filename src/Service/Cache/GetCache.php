<?php

declare(strict_types=1);

namespace App\Service\Cache;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

trait GetCache
{
    private FilesystemAdapter $cache;
    private ManagerRegistry $doctrine;
    private string $persistentObjectName;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->cache = new FilesystemAdapter();
        $this->doctrine = $doctrine;
    }

    public function getCount(string $key, string $class): int
    {
        $this->persistentObjectName = $class;

        $count = $this->cache->get($key, fn () => $this->countItems());

        return (int) $count;
    }

    private function countItems(): int
    {
        return (int) $this->doctrine
            ->getRepository($this->persistentObjectName)
            ->countAll();
    }
}
