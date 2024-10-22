<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 18.08.2018
 * Time: 17:29.
 */

namespace App\Service;

use App\Validator\PhotoRequirements;
use Gregwar\Image\Image;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\ByteString;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

final readonly class FileUploader
{
    private Filesystem $fileSystem;

    public function __construct(
        #[Autowire('%images_directory%')]
        private string $targetDirectory,
    ) {
        $this->fileSystem = new Filesystem();
    }

    public function validate(UploadedFile $file): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate(
            $file,
            [
                new PhotoRequirements(),
            ]
        );
    }

    /**
     * @throws \Exception
     */
    public function upload(UploadedFile $file): string
    {
        $fileName = ByteString::fromRandom(20).'.'.$file->guessExtension();

        // Full
        $file->move($this->targetDirectory.'/full/', $fileName);

        // Small
        Image::open($this->targetDirectory.'/full/'.$fileName)
            ->zoomCrop(500, 300, 'transparent', 'center', 'center')
            ->save($this->targetDirectory.'/small/'.$fileName);

        // Medium
        Image::open($this->targetDirectory.'/full/'.$fileName)
            ->zoomCrop(700, 420, 'transparent', 'center', 'center')
            ->save($this->targetDirectory.'/medium/'.$fileName);

        // Large
        Image::open($this->targetDirectory.'/full/'.$fileName)
            ->cropResize(1200, 800, 'transparent')
            ->save($this->targetDirectory.'/large/'.$fileName);

        return $fileName;
    }

    public function remove(string $fileName): void
    {
        $folders = [
            '/small/',
            '/medium/',
            '/full/',
            '/large/',
        ];

        foreach ($folders as $folder) {
            $this->fileSystem->remove($this->targetDirectory.$folder.$fileName);
        }
    }
}
