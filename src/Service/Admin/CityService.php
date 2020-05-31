<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Entity\City;
use App\Service\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class CityService extends AbstractService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        CsrfTokenManagerInterface $tokenManager,
        SessionInterface $session,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($tokenManager, $session);
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
