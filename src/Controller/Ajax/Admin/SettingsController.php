<?php

declare(strict_types=1);

namespace App\Controller\Ajax\Admin;

use App\Controller\Ajax\AjaxController;
use App\Service\Admin\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class SettingsController extends AbstractController implements AjaxController
{
    public function __construct(private readonly SettingsService $service)
    {
    }

    /**
     * @throws \Exception
     */
    #[Route(path: '/admin/setting/upload_header_image', name: 'admin_setting_upload_header_image', methods: ['POST'])]
    public function uploadHeaderImage(Request $request): JsonResponse
    {
        // Upload custom header image
        return $this->service->uploadImage('header_image', $request);
    }

    /**
     * @throws \Exception
     */
    #[Route(path: '/admin/setting/upload_logo_image', name: 'admin_setting_upload_logo_image', methods: ['POST'])]
    public function uploadLogoImage(Request $request): JsonResponse
    {
        // Upload custom logo image
        return $this->service->uploadImage('logo_image', $request);
    }
}
