<?php

declare(strict_types=1);

namespace App\Controller\User;

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
    #[Route(path: '/user/photo/{id<\d+>}/edit', name: 'user_photo_edit')]
    #[IsGranted('PROPERTY_EDIT', subject: 'property', message: 'You cannot change this property.')]
    public function edit(Request $request, Property $property): Response
    {
        $photos = $property->getPhotos();

        return $this->render('user/photo/edit.html.twig', [
            'photos' => $photos,
            'property_id' => $property->getId(),
            'site' => $this->site($request),
        ]);
    }

    /**
     * Deletes a Photo entity.
     */
    #[Route(path: '/user/photo/{id<\d+>}/delete', name: 'user_photo_delete', methods: ['POST'])]
    public function delete(Request $request, Photo $photo, FileUploader $fileUploader): Response
    {
        $property = $photo->getProperty();

        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute(
                'user_photo_edit',
                ['id' => $property->getId()]
            );
        }

        // Check permissions
        $this->denyAccessUnlessGranted('PROPERTY_EDIT', $property);

        // Delete from db
        $em = $this->doctrine->getManager();
        $em->remove($photo);
        $em->flush();

        // Delete file from folder
        $fileUploader->remove($photo->getPhoto());

        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute(
            'user_photo_edit',
            ['id' => $property->getId()]
        );
    }
}
