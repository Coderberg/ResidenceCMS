<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Entity\Property;
use App\Message\DeletePhotos;
use App\Service\AbstractService;
use App\Utils\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Messenger\MessageBusInterface;

final class PropertyService extends AbstractService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var Slugger
     */
    private $slugger;

    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus,
        Slugger $slugger
    ) {
        parent::__construct($container);
        $this->em = $entityManager;
        $this->messageBus = $messageBus;
        $this->slugger = $slugger;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function create(Property $property): void
    {
        // Make slug
        $slug = $this->slugger->slugify($property->getTitle() ?? 'property');

        $property->setSlug($slug);
        $property->setPublishedAt(new \DateTime('now'));
        $property->setPublished(true);
        $property->setPriorityNumber((int) ($property->getPriorityNumber()));
        $this->save($property);
        $this->clearCache('properties_count');
        $this->addFlash('success', 'message.created');
    }

    /**
     * Count all properties.
     *
     * @throws InvalidArgumentException
     */
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
        $slug = $this->slugger->slugify($property->getTitle() ?? 'property');
        $property->setSlug($slug);
        $property->setPriorityNumber((int) ($property->getPriorityNumber()));
        $this->em->flush();
        $this->addFlash('success', 'message.updated');
    }

    public function save(Property $property): void
    {
        $this->em->persist($property);
        $this->em->flush();
    }

    public function remove(Property $property): void
    {
        $this->em->remove($property);
        $this->em->flush();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete(Property $property): void
    {
        $this->messageBus->dispatch(new DeletePhotos($property));
        $this->remove($property);
        $this->clearCache('properties_count');
        $this->addFlash('success', 'message.deleted');
    }
}
