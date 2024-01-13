<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\DeletePhotos;
use App\Service\FileUploader;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeletePhotosHandler
{
    public function __construct(private FileUploader $fileUploader)
    {
    }

    public function __invoke(DeletePhotos $deletePhotos): void
    {
        $photos = $deletePhotos->getProperty()->getPhotos();

        foreach ($photos as $photo) {
            $this->fileUploader->remove($photo->getPhoto());
        }
    }
}
