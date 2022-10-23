<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Entity\Property;
use App\Entity\User;
use App\Form\Type\PropertyType;
use App\Service\User\PropertyService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PropertyController extends BaseController
{
    #[Route(path: '/user/property', defaults: ['page' => 1], methods: ['GET'], name: 'user_property')]
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
            'form' => $form->createView(),
            'site' => $this->site($request),
            'isHtmlAllowed' => $isHtmlAllowed,
        ]);
    }

    /**
     * Displays a form to edit an existing Property entity.
     */
    #[Route(path: '/user/property/{id<\d+>}/edit', methods: ['GET', 'POST'], name: 'user_property_edit')]
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
            'form' => $form->createView(),
            'site' => $this->site($request),
            'isHtmlAllowed' => $isHtmlAllowed,
        ]);
    }
}
