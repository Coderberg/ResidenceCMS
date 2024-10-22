<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Property;
use App\Form\Type\PropertyType;
use App\Repository\FilterRepository;
use App\Service\Admin\PropertyService;
use App\Transformer\RequestToArrayTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PropertyController extends BaseController
{
    #[Route(path: '/admin/property', name: 'admin_property', defaults: ['page' => 1], methods: ['GET'])]
    public function index(
        Request $request,
        FilterRepository $repository,
        RequestToArrayTransformer $transformer,
    ): Response {
        $searchParams = $transformer->transform($request);
        $properties = $repository->findByFilter($searchParams);

        return $this->render('admin/property/index.html.twig', [
            'site' => $this->site($request),
            'properties' => $properties,
            'searchParams' => $searchParams,
        ]);
    }

    #[Route(path: '/admin/property/new', name: 'admin_property_new')]
    public function new(Request $request, PropertyService $service): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->create($property);

            return $this->redirectToRoute('admin_photo_edit', ['id' => $property->getId()]);
        }

        return $this->render('admin/property/new.html.twig', [
            'site' => $this->site($request),
            'property' => $property,
            'form' => $form,
        ]);
    }

    /**
     * Displays a form to edit an existing Property entity.
     */
    #[Route(
        path: '/admin/property/{id}/edit',
        name: 'admin_property_edit',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, Property $property, PropertyService $service): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->update($property);

            return $this->redirectToRoute('admin_property');
        }

        return $this->render('admin/property/edit.html.twig', [
            'site' => $this->site($request),
            'form' => $form,
        ]);
    }

    /**
     * Deletes a Property entity.
     */
    #[Route(
        path: '/property/{id}/delete',
        name: 'admin_property_delete',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['POST']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Property $property, PropertyService $service): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->getPayload()->get('token'))) {
            return $this->redirectToRoute('admin_property');
        }

        $service->delete($property);

        return $this->redirectToRoute('admin_property');
    }
}
