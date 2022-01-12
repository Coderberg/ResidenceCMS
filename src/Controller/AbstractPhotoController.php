<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Property;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;

abstract class AbstractPhotoController extends AbstractController
{
    protected ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    protected function uploadPhoto(Property $property, Request $request, FileUploader $fileUploader): JsonResponse
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        $violations = $fileUploader->validate($uploadedFile);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            $violation = $violations[0];
            $this->addFlash('danger', $violation->getMessage());

            return new JsonResponse(['status' => 'fail'], 422);
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

    protected function sortPhotos(Request $request, Property $property): JsonResponse
    {
        $ids = $request->request->all('ids');
        $repository = $this->doctrine->getRepository(Photo::class);
        $repository->reorderPhotos($property, $ids);

        return new JsonResponse(['status' => 'ok']);
    }
}
