<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Entity\Property;
use App\Message\DeletePhotos;
use App\Service\AbstractService;
use App\Utils\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

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
        CsrfTokenManagerInterface $tokenManager,
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus,
        Slugger $slugger
    ) {
        parent::__construct($tokenManager, $session);
        $this->em = $entityManager;
        $this->messageBus = $messageBus;
        $this->slugger = $slugger;
    }

    public function create(Property $property): void
    {
        // Make slug
        $slug = $this->slugger->slugify($property->getTitle() ?? 'property');

        $property->setSlug($slug);
        $property->setPublishedAt(new \DateTime('now'));
        $property->setState('published');
        $property->setPriorityNumber((int) ($property->getPriorityNumber()));
        $this->save($property);
        $this->clearCache('properties_count');
        $this->addFlash('success', 'message.created');
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

    public function delete(Property $property): void
    {
        $this->messageBus->dispatch(new DeletePhotos($property));
        $this->remove($property);
        $this->clearCache('properties_count');
        $this->addFlash('success', 'message.deleted');
    }
}
