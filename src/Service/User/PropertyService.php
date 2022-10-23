<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\Property;
use App\Repository\UserPropertyRepository;
use App\Service\Admin\PropertyService as Service;
use App\Transformer\PropertyTransformer;
use App\Transformer\RequestToArrayTransformer;
use App\Utils\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class PropertyService extends Service
{
    public function __construct(
        CsrfTokenManagerInterface $tokenManager,
        RequestStack $requestStack,
        EntityManagerInterface $em,
        MessageBusInterface $messageBus,
        Slugger $slugger,
        private PropertyTransformer $propertyTransformer,
        private UserPropertyRepository $repository,
        private RequestToArrayTransformer $transformer,
        private TokenStorageInterface $tokenStorage
    ) {
        parent::__construct($tokenManager, $requestStack, $em, $messageBus, $slugger);
    }

    public function getUserProperties(Request $request): PaginationInterface
    {
        $searchParams = $this->transformer->transform($request);
        $searchParams['user'] = $this->tokenStorage->getToken()->getUser()->getId();

        return $this->repository->findByUser($searchParams);
    }

    public function contentToPlainText(Property $property, bool $isHtmlAllowed): Property
    {
        if (!$isHtmlAllowed) {
            $property = $this->propertyTransformer->contentToPlainText($property);
        }

        return $property;
    }

    public function contentToHtml(Property $property, bool $isHtml): Property
    {
        if (!$isHtml) {
            $property = $this->propertyTransformer->contentToHtml($property);
        }

        return $property;
    }

    public function sanitizeHtml(Property $property, bool $isHtmlAllowed): Property
    {
        if (!$isHtmlAllowed) {
            $property = $this->propertyTransformer->contentToPlainText($property);
            $property = $this->propertyTransformer->contentToHtml($property);
        }

        return $property;
    }
}
