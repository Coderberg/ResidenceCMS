<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Property;
use App\Utils\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class PropertyService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Slugger
     */
    private $slugger;

    public function __construct(EntityManagerInterface $entityManager, Slugger $slugger)
    {
        $this->em = $entityManager;
        $this->slugger = $slugger;
    }

    public function create(Property $property): void
    {
        // Make slug
        $slug = $this->slugger->slugify($property->getTitle());

        $property->setSlug($slug);
        $property->setPublishedAt(new \DateTime('now'));
        $property->setPublished(true);
        $property->setPriorityNumber((int) ($property->getPriorityNumber()));
        $this->save($property);
        $this->clearCache();
    }

    public function findLatest(int $page, string $orderBy = 'priority'): Pagerfanta
    {
        $repository = $this->em->getRepository(Property::class);

        return $repository->findLatest($page, $orderBy);
    }

    public function countAll(): int
    {
        $cache = new FilesystemAdapter();

        $count = $cache->get('properties_count', function () {
            return $this->em->getRepository(Property::class)->countAll();
        });

        return (int) $count;
    }

    public function update(Property $property): void
    {
        $slug = $this->slugger->slugify($property->getTitle());
        $property->setSlug($slug);
        $property->setPriorityNumber((int) ($property->getPriorityNumber()));
        $this->em->flush();
    }

    public function save(Property $property): void
    {
        $this->em->persist($property);
        $this->em->flush();
    }

    public function remove($object): void
    {
        $this->em->remove($object);
        $this->em->flush();
    }

    public function delete(Property $property): void
    {
        // Search photos
        $photos = $property->getPhotos();

        if ($photos) {
            // Remove photos
            foreach ($photos as $photo) {
                $this->remove($photo);
            }
        }

        $this->remove($property);
        $this->clearCache();
    }

    private function clearCache(): void
    {
        $cache = new FilesystemAdapter();
        $cache->delete('properties_count');
    }
}
