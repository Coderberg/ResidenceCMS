<?php

declare(strict_types=1);

namespace App\Service\Cache;

use App\Repository\CategoryRepository;
use App\Repository\CityRepository;
use App\Repository\DealTypeRepository;
use App\Repository\PageRepository;
use App\Repository\PropertyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Cache\InvalidArgumentException;
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

    /**
     * @throws InvalidArgumentException
     */
    public function getCount(string $key, string $class): int
    {
        $this->persistentObjectName = $class;

        $count = $this->cache->get($key, fn () => $this->countItems());

        return (int) $count;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    private function countItems(): int
    {
        /** @var PropertyRepository|CityRepository|DealTypeRepository|CategoryRepository|PageRepository|UserRepository $repository */
        $repository = $this->doctrine->getRepository($this->persistentObjectName);

        return $repository->countAll();
    }
}
