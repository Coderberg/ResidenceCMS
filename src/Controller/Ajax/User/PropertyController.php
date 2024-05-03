<?php

declare(strict_types=1);

namespace App\Controller\Ajax\User;

use App\Controller\Ajax\AjaxController;
use App\Entity\Property;
use App\Repository\UserPropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PropertyController extends AbstractController implements AjaxController
{
    #[Route(
        path: '/user/property/{id}/update',
        name: 'user_property_update',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET']
    )]
    #[IsGranted('PROPERTY_EDIT', subject: 'property', message: 'You cannot change this property.')]
    public function update(Request $request, Property $property, UserPropertyRepository $repository): JsonResponse
    {
        $state = $request->query->get('state');

        if (!\in_array($state, ['published', 'private'], true)) {
            return new JsonResponse(['status' => 'fail'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($repository->changeState($property, $state)) {
            return new JsonResponse(['status' => 'ok']);
        }

        return new JsonResponse(['status' => 'fail'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
