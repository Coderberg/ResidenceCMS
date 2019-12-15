<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\SettingsRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;

final class SettingsService extends AbstractService
{
    /**
     * @var SettingsRepository
     */
    private $repository;

    /**
     * @var FileUploader
     */
    private $fileUploader;

    public function __construct(
        SettingsRepository $repository,
        FileUploader $fileUploader,
        ContainerInterface $container
    ) {
        parent::__construct($container);
        $this->repository = $repository;
        $this->fileUploader = $fileUploader;
    }

    /**
     * Update settings in database.
     */
    public function updateSettings(array $formData): void
    {
        $this->repository->updateSettings($formData);
        $this->addFlash('success', 'message.updated');
    }

    /**
     * Upload custom header image.
     *
     * @throws \Exception
     */
    public function uploadHeaderImage(Request $request): Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');

        if (!$this->isImageValid($uploadedFile)) {
            return new JsonResponse(['status' => 'error']);
        }

        $fileName = $this->fileUploader->upload($uploadedFile);
        $this->repository->updateSetting('header_image', $fileName);

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * Validate header image.
     */
    private function isImageValid(UploadedFile $uploadedFile): bool
    {
        $violations = $this->fileUploader->validate($uploadedFile);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            $violation = $violations[0];
            $this->addFlash('danger', $violation->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Reset a header image to the default image.
     */
    public function resetHeaderImage(Request $request): void
    {
        $setting = $this->repository->findOneBy(['setting_name' => 'header_image']);

        if ($setting && $this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            // Find filename
            $filename = $setting->getSettingValue();

            if ($filename) {
                // Delete
                $this->deleteHeaderImage($filename);
            }
        }
    }

    /**
     * Delete header image.
     */
    private function deleteHeaderImage(string $filename): void
    {
        // Delete file from folder
        $this->fileUploader->remove($filename);
        // Delete from db
        $this->repository->updateSetting('header_image', '');
        // Add flash message
        $this->addFlash('success', 'message.deleted');
    }
}
