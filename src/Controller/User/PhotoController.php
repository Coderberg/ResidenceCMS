<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Entity\Photo;
use App\Entity\Property;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;

final class PhotoController extends BaseController
{
    /**
     * @Route("/user/photo/{id<\d+>}/upload", name="user_photo_upload", methods={"POST"})
     * @IsGranted("PROPERTY_EDIT", subject="property", message="You cannot change this property.")
     */
    public function upload(Property $property, Request $request, FileUploader $fileUploader): Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        $violations = $fileUploader->validate($uploadedFile);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            $violation = $violations[0];
            $this->addFlash('danger', $violation->getMessage());

            return new JsonResponse(['status' => 'error']);
        }

        $fileName = $fileUploader->upload($uploadedFile);

        $photo = new Photo();
        $photo->setProperty($property)
                ->setSortOrder(0)
                ->setPhoto($fileName);

        $em = $this->getDoctrine()->getManager();
        $em->persist($photo);
        $em->flush();

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @Route("/user/photo/{id<\d+>}/edit", name="user_photo_edit")
     * @IsGranted("PROPERTY_EDIT", subject="property", message="You cannot change this property.")
     */
    public function edit(Property $property): Response
    {
        $photos = $property->getPhotos();

        return $this->render('user/photo/edit.html.twig', [
            'photos' => $photos,
            'property_id' => $property->getId(),
            'site' => $this->site(),
        ]);
    }

    /**
     * Sort photos.
     *
     * @Route("/user/photo/{id<\d+>}/sort",methods={"POST"}, name="user_photo_sort")
     * @IsGranted("PROPERTY_EDIT", subject="property", message="You cannot change this property.")
     */
    public function sort(Request $request, Property $property): Response
    {
        $ids = $request->request->get('ids');
        $repository = $this->getDoctrine()->getRepository(Photo::class);
        $repository->reorderPhotos($property, $ids);

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * Deletes a Photo entity.
     *
     * @Route("/user/photo/{id<\d+>}/delete", methods={"POST"}, name="user_photo_delete")
     */
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
        $em = $this->getDoctrine()->getManager();
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
