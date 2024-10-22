<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Repository\SettingsRepository;
use App\Service\AbstractService;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Validator\ConstraintViolation;

final class SettingsService extends AbstractService
{
    public function __construct(
        CsrfTokenManagerInterface $tokenManager,
        RequestStack $requestStack,
        private readonly SettingsRepository $repository,
        private readonly FileUploader $fileUploader,
    ) {
        parent::__construct($tokenManager, $requestStack);
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
    public function uploadImage(string $type, Request $request): JsonResponse
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');

        if (!$this->isImageValid($uploadedFile)) {
            return new JsonResponse(['status' => 'fail'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $fileName = $this->fileUploader->upload($uploadedFile);
        $this->repository->updateSetting($type, $fileName);

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
    public function resetImage(string $type, Request $request): void
    {
        $setting = $this->repository->findOneBy(['setting_name' => $type]);

        if ($setting && $this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            // Find filename
            $filename = $setting->getSettingValue();

            if ($filename) {
                // Delete
                $this->deleteImage($filename, $type);
            }
        }
    }

    /**
     * Delete header image.
     */
    private function deleteImage(string $filename, string $type = 'header_image'): void
    {
        // Delete file from folder
        $this->fileUploader->remove($filename);
        // Delete from db
        $this->repository->updateSetting($type);
        // Add flash message
        $this->addFlash('success', 'message.deleted');
    }
}
