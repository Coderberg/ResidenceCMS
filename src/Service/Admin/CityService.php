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

    public function create(City $category): void
    {
        $this->save($category);
        $this->clearCache('cities_count');
        $this->addFlash('success', 'message.created');
    }

    public function update(City $category): void
    {
        $this->save($category);
        $this->addFlash('success', 'message.updated');
    }

    public function remove(City $category): void
    {
        $this->em->remove($category);
        $this->em->flush();
        $this->clearCache('cities_count');
        $this->addFlash('success', 'message.deleted');
    }

    private function save(City $category): void
    {
        $this->em->persist($category);
        $this->em->flush();
    }
}
