<?php

declare(strict_types=1);

namespace App\Controller\User\Ajax;

use App\Controller\AjaxController;
use App\Controller\BaseAjaxController;
use App\Entity\Photo;
use App\Entity\Property;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;

final class PhotoController extends BaseAjaxController implements AjaxController
{
    /**
     * @Route("/user/photo/{id<\d+>}/upload", name="user_photo_upload", methods={"POST"})
     * @IsGranted("PROPERTY_EDIT", subject="property", message="You cannot change this property.")
     */
    public function upload(Property $property, Request $request, FileUploader $fileUploader): JsonResponse
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

    /**
     * Sort photos.
     *
     * @Route("/user/photo/{id<\d+>}/sort",methods={"POST"}, name="user_photo_sort")
     * @IsGranted("PROPERTY_EDIT", subject="property", message="You cannot change this property.")
     */
    public function sort(Request $request, Property $property): JsonResponse
    {
        $ids = $request->request->all('ids');
        $repository = $this->doctrine->getRepository(Photo::class);
        $repository->reorderPhotos($property, $ids);

        return new JsonResponse(['status' => 'ok']);
    }
}
