<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Property;
use App\Form\Type\PropertyType;
use App\Repository\FilterRepository;
use App\Service\Admin\PropertyService;
use App\Transformer\RequestToArrayTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PropertyController extends BaseController
{
    /**
     * @Route("/admin/property", defaults={"page": "1"}, methods={"GET"}, name="admin_property")
     */
    public function index(Request $request, FilterRepository $repository, RequestToArrayTransformer $transformer): Response
    {
        $searchParams = $transformer->transform($request);
        $properties = $repository->findByFilter($searchParams);

        return $this->render('admin/property/index.html.twig', [
            'site' => $this->site(),
            'properties' => $properties,
            'searchParams' => $searchParams,
        ]);
    }

    /**
     * @Route("/admin/property/new", name="admin_property_new")
     */
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
            'site' => $this->site(),
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Property entity.
     *
     * @Route("/admin/property/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_property_edit")
     */
    public function edit(Request $request, Property $property, PropertyService $service): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->update($property);

            return $this->redirectToRoute('admin_property');
        }

        return $this->render('admin/property/edit.html.twig', [
            'site' => $this->site(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Property entity.
     *
     * @Route("/property/{id<\d+>}/delete", methods={"POST"}, name="admin_property_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Property $property, PropertyService $service): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_property');
        }

        $service->delete($property);

        return $this->redirectToRoute('admin_property');
    }
}
