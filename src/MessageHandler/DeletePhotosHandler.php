<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\DeletePhotos;
use App\Service\FileUploader;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeletePhotosHandler implements MessageHandlerInterface
{
    /**
     * @var FileUploader
     */
    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function __invoke(DeletePhotos $deletePhotos)
    {
        $photos = $deletePhotos->getProperty()->getPhotos();

        foreach ($photos as $photo) {
            $this->fileUploader->remove($photo->getPhoto());
        }
    }
}
