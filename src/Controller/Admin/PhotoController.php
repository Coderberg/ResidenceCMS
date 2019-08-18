<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Photo;
use App\Entity\Property;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;

final class PhotoController extends AbstractController
{
    /**
     * @Route("/admin/photo/{id<\d+>}/upload", name="admin_photo_upload", methods={"POST"})
     */
    public function upload(Request $request, FileUploader $fileUploader): Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        $violations = $fileUploader->validate($uploadedFile);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            $violation = $violations[0];

            return new JsonResponse(['status' => 'error', 'message' => $violation->getMessage()]);
        }

        $fileName = $fileUploader->upload($uploadedFile);

        $property = $this->getDoctrine()
                ->getRepository(Property::class)
                ->find($request->attributes->get('id'));

        $photo = new Photo();
        $photo->setProperty($property)
                ->setPriority(0)
                ->setPhoto($fileName);

        $em = $this->getDoctrine()->getManager();
        $em->persist($photo);
        $em->flush();

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @Route("/admin/photo/{id<\d+>}/edit", name="admin_photo_edit")
     */
    public function edit(Request $request): Response
    {
        $property = $this->getDoctrine()
            ->getRepository(Property::class)
            ->find($request->attributes->get('id'));

        $photos = $property->getPhotos();

        return $this->render('admin/photo/edit.html.twig', [
            'photos' => $photos,
            'property_id' => $property->getId(),
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
