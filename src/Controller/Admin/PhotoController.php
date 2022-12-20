<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Photo;
use App\Entity\Property;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PhotoController extends BaseController
{
    #[Route(path: '/admin/photo/{id<\d+>}/edit', name: 'admin_photo_edit')]
    public function edit(Request $request, Property $property): Response
    {
        $photos = $property->getPhotos();

        return $this->render('admin/photo/edit.html.twig', [
            'site' => $this->site($request),
            'photos' => $photos,
            'property_id' => $property->getId(),
        ]);
    }

    /**
     * Deletes a Photo entity.
     */
    #[Route(path: '/property/{property_id<\d+>}/photo/{id<\d+>}/delete', name: 'admin_photo_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Photo $photo, FileUploader $fileUploader): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute(
                'admin_photo_edit',
                ['id' => $request->attributes->get('property_id')]
            );
        }

        // Delete from db
        $em = $this->doctrine->getManager();
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
