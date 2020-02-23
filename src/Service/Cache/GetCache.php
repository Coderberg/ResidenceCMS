<?php

declare(strict_types=1);

namespace App\Service\Cache;

use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

trait GetCache
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var FilesystemAdapter
     */
    private $cache;

    /**
     * @var string
     */
    private $persistentObjectName;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->cache = new FilesystemAdapter();
    }

    public function getCount(string $key, string $class): int
    {
        $this->persistentObjectName = $class;

        $count = $this->cache->get($key, function () {
            return $this->countItems();
        });

        return (int) $count;
    }

    private function countItems(): int
    {
        return (int) $this->container->get('doctrine')
            ->getRepository($this->persistentObjectName)
            ->countAll();
    }
}
