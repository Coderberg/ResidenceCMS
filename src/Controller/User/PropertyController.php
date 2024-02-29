<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Entity\Property;
use App\Entity\User;
use App\Form\Type\PropertyType;
use App\Service\User\PropertyService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PropertyController extends BaseController
{
    #[Route(path: '/user/property', name: 'user_property', defaults: ['page' => 1], methods: ['GET'])]
    public function index(Request $request, PropertyService $service): Response
    {
        $properties = $service->getUserProperties($request);

        return $this->render('user/property/index.html.twig', [
            'properties' => $properties,
            'site' => $this->site($request),
        ]);
    }

    #[Route(path: '/user/property/new', name: 'user_property_new')]
    public function new(Request $request, PropertyService $service): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if (!$user->isVerified()) {
            return $this->redirectToRoute('user_property');
        }
        $isHtmlAllowed = $this->isGranted('USE_HTML');
        $property = new Property();
        $property->setAuthor($user);
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $property = $service->sanitizeHtml($property, $isHtmlAllowed);
            $service->create($property);

            return $this->redirectToRoute('user_photo_edit', ['id' => $property->getId()]);
        }

        return $this->render('user/property/new.html.twig', [
            'property' => $property,
            'form' => $form,
            'site' => $this->site($request),
            'isHtmlAllowed' => $isHtmlAllowed,
        ]);
    }

    /**
     * Displays a form to edit an existing Property entity.
     */
    #[Route(
        path: '/user/property/{id}/edit',
        name: 'user_property_edit',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET', 'POST']
    )]
    #[IsGranted('PROPERTY_EDIT', subject: 'property', message: 'You cannot change this property.')]
    public function edit(Request $request, Property $property, PropertyService $service): Response
    {
        $isHtmlAllowed = $this->isGranted('USE_HTML');
        $property = $service->contentToPlainText($property, $isHtmlAllowed);
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $property = $service->contentToHtml($property, $isHtmlAllowed);
            $service->update($property);

            return $this->redirectToRoute('user_photo_edit', ['id' => $property->getId()]);
        }

        return $this->render('user/property/edit.html.twig', [
            'form' => $form,
            'site' => $this->site($request),
            'isHtmlAllowed' => $isHtmlAllowed,
        ]);
    }
}
