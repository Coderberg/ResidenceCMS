<?php

namespace App\Controller\Admin;

use App\Entity\Photo;
use App\Entity\Property;
use App\Form\PhotoType;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PhotoController extends AbstractController
{
    /**
     * @Route("/admin/photo", name="admin_photo")
     */
    public function index()
    {
        return $this->render('admin/photo/index.html.twig', [
            'controller_name' => 'PhotoController',
        ]);
    }

    /**
     * @Route("/admin/photo/{id<\d+>}/edit", name="admin_photo_edit")
     */
    public function edit(Request $request, FileUploader $fileUploader)
    {
        $photo = new Photo();

        $property = $this->getDoctrine()
            ->getRepository(Property::class)
            ->find($request->attributes->get('id'));

        $photo->setProperty($property);

        $photos = $property->getPhotos();

        $form = $this->createForm(PhotoType::class, $photo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $photo->getPhoto();
            $fileName = $fileUploader->upload($file);

            $photo->setPhoto($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();

            $this->addFlash('success', 'message.uploaded');

            return $this->redirectToRoute('admin_photo_edit', ['id' => $property->getId()]);
        }

        return $this->render('admin/photo/edit.html.twig', [
            'photos' => $photos,
            'property_id' => $property->getId(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Photo entity.
     *
     * @Route("/property/{property_id<\d+>}/photo/{id<\d+>}/delete", methods={"POST"}, name="admin_photo_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Photo $photo, FileUploader $fileUploader): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute(
                'admin_photo_edit',
                ['id' => $request->attributes->get('property_id')]
            );
        }

        // Delete from db
        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();

        // Delete file from folder
        $fileUploader->remove($photo->getPhoto());

        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute(
            'admin_photo_edit',
            ['id' => $request->attributes->get('property_id')]
        );
    }
}
