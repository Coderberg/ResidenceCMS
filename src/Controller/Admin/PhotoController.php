<?php

declare(strict_types=1);

namespace App\Controller\Admin;

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
     * @Route("/admin/photo/{id<\d+>}/upload", name="admin_photo_upload", methods={"POST"})
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

        $em = $this->doctrine->getManager();
        $em->persist($photo);
        $em->flush();

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @Route("/admin/photo/{id<\d+>}/edit", name="admin_photo_edit")
     */
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
     * Sort photos.
     *
     * @Route("/admin/photo/{id<\d+>}/sort",methods={"POST"}, name="admin_photo_sort")
     */
    public function sort(Request $request, Property $property): Response
    {
        $ids = $request->request->all('ids');
        $repository = $this->doctrine->getRepository(Photo::class);
        $repository->reorderPhotos($property, (array) $ids);

        return new JsonResponse(['status' => 'ok']);
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
