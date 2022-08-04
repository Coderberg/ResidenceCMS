<?php

declare(strict_types=1);

namespace App\Controller\Admin\Settings;

use App\Form\Type\MapSettingsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MapSettingsController extends AbstractSettingsController
{
    /**
     * @Route("/admin/settings/map", name="admin_map_settings")
     */
    public function mapSettings(Request $request): Response
    {
        $settings = $this->repository->findAllAsArray();

        $form = $this->createForm(MapSettingsType::class, $settings);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->updateSettings($form->getNormData());

            return $this->redirectToRoute('admin_map_settings');
        }

        return $this->render('admin/settings/map_settings.html.twig', [
            'site' => $settings,
            'form' => $form->createView(),
        ]);
    }
}
