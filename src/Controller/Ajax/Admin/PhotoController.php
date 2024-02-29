<?php

declare(strict_types=1);

namespace App\Controller\Ajax\Admin;

use App\Controller\AbstractPhotoController;
use App\Controller\Ajax\AjaxController;
use App\Entity\Property;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class PhotoController extends AbstractPhotoController implements AjaxController
{
    #[Route(
        path: '/admin/photo/{id}/upload',
        name: 'admin_photo_upload',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['POST']
    )]
    public function upload(Property $property, Request $request, FileUploader $fileUploader): JsonResponse
    {
        return $this->uploadPhoto($property, $request, $fileUploader);
    }

    /**
     * Sort photos.
     */
    #[Route(
        path: '/admin/photo/{id}/sort',
        name: 'admin_photo_sort',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['POST'])]
    public function sort(Request $request, Property $property): JsonResponse
    {
        return $this->sortPhotos($request, $property);
    }
}
