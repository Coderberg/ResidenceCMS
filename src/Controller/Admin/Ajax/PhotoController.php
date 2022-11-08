<?php

declare(strict_types=1);

namespace App\Controller\Admin\Ajax;

use App\Controller\AbstractPhotoController;
use App\Controller\AjaxController;
use App\Entity\Property;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class PhotoController extends AbstractPhotoController implements AjaxController
{
    #[Route(path: '/admin/photo/{id<\d+>}/upload', name: 'admin_photo_upload', methods: ['POST'])]
    public function upload(Property $property, Request $request, FileUploader $fileUploader): JsonResponse
    {
        return $this->uploadPhoto($property, $request, $fileUploader);
    }

    /**
     * Sort photos.
     */
    #[Route(path: '/admin/photo/{id<\d+>}/sort', name: 'admin_photo_sort', methods: ['POST'])]
    public function sort(Request $request, Property $property): JsonResponse
    {
        return $this->sortPhotos($request, $property);
    }
}
