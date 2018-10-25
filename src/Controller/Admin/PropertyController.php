<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Utils\Slugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PropertyController extends AbstractController
{
    /**
     * @Route("/admin", defaults={"page": "1"}, methods={"GET"}, name="admin_property")
     * @Route("/admin/page/{page<[1-9]\d*>}", methods={"GET"}, name="admin_property_paginated")
     */
    public function index(?int $page)
    {
        // Get properties
        $repository = $this->getDoctrine()->getRepository(Property::class);

        $properties = $repository->findLatest($page);

        return $this->render('admin/property/index.html.twig', [
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("/admin/property/new", name="admin_property_new")
     */
    public function new(Request $request, Slugger $slugger)
    {
        $property = new Property();
        $property->setAuthor($this->getUser());
        $property->setPublishedAt(new \DateTime('now'));
        $property->setPublished(1);

        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Make slug
            $slug = $slugger->slugify($property->getTitle());
            $property->setSlug($slug);

            $em = $this->getDoctrine()->getManager();
            $em->persist($property);
            $em->flush();

            $this->addFlash('success', 'message.created');

            return $this->redirectToRoute('admin_photo_edit', ['id' => $property->getId()]);
        }

        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Property entity.
     *
     * @Route("/admin/property/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_property_edit")
     */
    public function edit(Request $request, Property $property, Slugger $slugger): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update slug
            $slug = $slugger->slugify($property->getTitle());
            $property->setSlug($slug);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_property');
        }

        return $this->render('admin/property/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Property entity.
     *
     * @Route("/property/{id<\d+>}/delete", methods={"POST"}, name="admin_property_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Property $property): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_property');
        }

        $em = $this->getDoctrine()->getManager();

        // Search photos
        $photos = $property->getPhotos();

        if ($photos) {
            // Remove photos
            foreach ($photos as $photo) {
                $property->removePhoto($photo);
            }
        }

        $em->remove($property);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_property');
    }
}
