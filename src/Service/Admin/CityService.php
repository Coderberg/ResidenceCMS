<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Entity\City;
use App\Service\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class CityService extends AbstractService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        parent::__construct($container);
        $this->em = $entityManager;
    }

    public function create(City $city): void
    {
        $this->save($city);
        $this->clearCache('cities_count');
        $this->addFlash('success', 'message.created');
    }

    public function update(City $city): void
    {
        $this->save($city);
        $this->addFlash('success', 'message.updated');
    }

    public function remove(City $city): void
    {
        $this->em->remove($city);
        $this->em->flush();
        $this->clearCache('cities_count');
        $this->addFlash('success', 'message.deleted');
    }

    private function save(City $city): void
    {
        $this->em->persist($city);
        $this->em->flush();
    }
}
