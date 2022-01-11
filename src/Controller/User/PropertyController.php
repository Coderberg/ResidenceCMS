<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Entity\Property;
use App\Entity\User;
use App\Form\Type\PropertyType;
use App\Service\Admin\PropertyService as AdminPropertyService;
use App\Service\User\PropertyService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
            'site' => $this->site($request),
        ]);
    }

    /**
     * @Route("/user/property/new", name="user_property_new")
     */
    public function new(Request $request, AdminPropertyService $service): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $property = new Property();
        $property->setAuthor($user);
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->create($property);

            return $this->redirectToRoute('user_photo_edit', ['id' => $property->getId()]);
        }

        return $this->render('user/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'site' => $this->site($request),
        ]);
    }

    /**
     * Displays a form to edit an existing Property entity.
     *
     * @Route("/user/property/{id<\d+>}/edit",methods={"GET", "POST"}, name="user_property_edit")
     * @IsGranted("PROPERTY_EDIT", subject="property", message="You cannot change this property.")
     */
    public function edit(Request $request, Property $property, AdminPropertyService $service): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->update($property);

            return $this->redirectToRoute('user_photo_edit', ['id' => $property->getId()]);
        }

        return $this->render('user/property/edit.html.twig', [
            'form' => $form->createView(),
            'site' => $this->site($request),
        ]);
    }
}
