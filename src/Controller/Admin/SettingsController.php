<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Form\Type\FilterSettingsType;
use App\Form\Type\SettingsType;
use App\Repository\SettingsRepository;
use App\Service\Admin\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SettingsController extends AbstractController
{
    /**
     * @var SettingsRepository
     */
    private $repository;

    /**
     * @var SettingsService
     */
    private $service;

    public function __construct(SettingsRepository $repository, SettingsService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * @Route("/admin/settings", name="admin_settings")
     */
    public function settings(Request $request): Response
    {
        $settings = $this->repository->findAllAsArray();

        $form = $this->createForm(SettingsType::class, $settings);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->updateSettings($form->getNormData());

            return $this->redirectToRoute('admin_settings');
        }

        return $this->render('admin/settings/settings.html.twig', [
            'site' => $settings,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/setting/header", name="admin_header_settings")
     */
    public function changeHeaderImage(Request $request): Response
    {
        $settings = $this->repository->findAllAsArray();

        $form = $this->createForm(FilterSettingsType::class, $settings);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->updateSettings($form->getNormData());

            return $this->redirectToRoute('admin_header_settings');
        }

        return $this->render('admin/settings/header_settings.html.twig', [
            'site' => $settings,
            'header_image' => $settings['header_image'],
            'logo_image' => $settings['logo_image'],
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/setting/upload_header_image", methods={"POST"}, name="admin_setting_upload_header_image")
     *
     * @throws \Exception
     */
    public function uploadHeaderImage(Request $request): Response
    {
        // Upload custom header image
        return $this->service->uploadImage('header_image', $request);
    }

    /**
     * @Route("/admin/setting/upload_logo_image", methods={"POST"}, name="admin_setting_upload_logo_image")
     *
     * @throws \Exception
     */
    public function uploadLogoImage(Request $request): Response
    {
        // Upload custom header image
        return $this->service->uploadImage('logo_image', $request);
    }

    /**
     * @Route("/admin/setting/delete_header_image", methods={"POST"}, name="admin_setting_delete_header_image")
     */
    public function deleteHeaderImage(Request $request): Response
    {
        // Reset a header image to the default image.
        $this->service->resetImage('header_image', $request);

        return $this->redirectToRoute('admin_header_settings');
    }

    /**
     * @Route("/admin/setting/delete_logo_image", methods={"POST"}, name="admin_setting_delete_logo_image")
     */
    public function deleteLogoImage(Request $request): Response
    {
        // Reset a header image to the default image.
        $this->service->resetImage('logo_image', $request);

        return $this->redirectToRoute('admin_header_settings');
    }
}
