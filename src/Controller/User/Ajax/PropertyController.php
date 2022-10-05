<?php

declare(strict_types=1);

namespace App\Controller\User\Ajax;

use App\Controller\AjaxController;
use App\Entity\Property;
use App\Repository\UserPropertyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class PropertyController extends AbstractController implements AjaxController
{
    #[Route(path: '/user/property/{id<\d+>}/update', methods: ['GET'], name: 'user_property_update')]
    #[IsGranted('PROPERTY_EDIT', subject: 'property', message: 'You cannot change this property.')]
    public function update(Request $request, Property $property, UserPropertyRepository $repository): JsonResponse
    {
        $state = $request->query->get('state');

        if (!\in_array($state, ['published', 'private'], true)) {
            return new JsonResponse(['status' => 'fail'], 422);
        }

        if ($repository->changeState($property, $state)) {
            return new JsonResponse(['status' => 'ok']);
        }

        return new JsonResponse(['status' => 'fail'], 500);
    }
}
