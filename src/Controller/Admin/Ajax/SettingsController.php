<?php

declare(strict_types=1);

namespace App\Controller\Admin\Ajax;

use App\Controller\AjaxController;
use App\Service\Admin\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class SettingsController extends AbstractController implements AjaxController
{
    public function __construct(private SettingsService $service)
    {
    }

    /**
     * @Route("/admin/setting/upload_header_image", methods={"POST"}, name="admin_setting_upload_header_image")
     *
     * @throws \Exception
     */
    public function uploadHeaderImage(Request $request): JsonResponse
    {
        // Upload custom header image
        return $this->service->uploadImage('header_image', $request);
    }

    /**
     * @Route("/admin/setting/upload_logo_image", methods={"POST"}, name="admin_setting_upload_logo_image")
     *
     * @throws \Exception
     */
    public function uploadLogoImage(Request $request): JsonResponse
    {
        // Upload custom logo image
        return $this->service->uploadImage('logo_image', $request);
    }
}
