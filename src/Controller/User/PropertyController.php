<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Entity\Property;
use App\Repository\UserPropertyRepository;
use App\Service\User\PropertyService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PropertyController extends BaseController
{
    /**
     * @Route("/user/property", defaults={"page": "1"}, methods={"GET"}, name="user_property")
     */
    public function index(Request $request, PropertyService $service): Response
    {
        $properties = $service->getUserProperties($request);

        return $this->render('user/property/index.html.twig', [
            'properties' => $properties,
            'site' => $this->site(),
        ]);
    }

    /**
     * @Route("/user/property/{id<\d+>}/publish", methods={"GET"}, name="user_property_publish")
     * @IsGranted("PROPERTY_EDIT", subject="property", message="You cannot change this property.")
     */
    public function publish(Property $property, UserPropertyRepository $repository)
    {
        if ($repository->changeState($property, 'published')) {
            return new JsonResponse(['status' => 'ok']);
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @Route("/user/property/{id<\d+>}/unpublish", methods={"GET"}, name="user_property_unpublish")
     * @IsGranted("PROPERTY_EDIT", subject="property", message="You cannot change this property.")
     */
    public function unPublish(Property $property, UserPropertyRepository $repository)
    {
        if ($repository->changeState($property, 'private')) {
            return new JsonResponse(['status' => 'ok']);
        }

        return new JsonResponse(['status' => 'error']);
    }
}
