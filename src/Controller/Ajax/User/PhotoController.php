<?php

declare(strict_types=1);

namespace App\Controller\Ajax\User;

use App\Controller\AbstractPhotoController;
use App\Controller\Ajax\AjaxController;
use App\Entity\Property;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PhotoController extends AbstractPhotoController implements AjaxController
{
    #[Route(path: '/user/photo/{id<\d+>}/upload', name: 'user_photo_upload', methods: ['POST'])]
    #[IsGranted('PROPERTY_EDIT', subject: 'property', message: 'You cannot change this property.')]
    public function upload(Property $property, Request $request, FileUploader $fileUploader): JsonResponse
    {
        return $this->uploadPhoto($property, $request, $fileUploader);
    }

    /**
     * Sort photos.
     */
    #[Route(path: '/user/photo/{id<\d+>}/sort', name: 'user_photo_sort', methods: ['POST'])]
    #[IsGranted('PROPERTY_EDIT', subject: 'property', message: 'You cannot change this property.')]
    public function sort(Request $request, Property $property): JsonResponse
    {
        return $this->sortPhotos($request, $property);
    }
}
