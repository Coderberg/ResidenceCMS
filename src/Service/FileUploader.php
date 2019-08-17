<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 18.08.2018
 * Time: 17:29.
 */

namespace App\Service;

use Gumlet\ImageResize;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file): string
    {
        $fileSystem = new Filesystem();

        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        // Small
        $image = new ImageResize($this->getTargetDirectory().'/'.$fileName);
        $image->resize(500, 300, $allow_enlarge = true);
        $image->save($this->getTargetDirectory().'/small/'.$fileName);

        // Medium
        $image = new ImageResize($this->getTargetDirectory().'/'.$fileName);
        $image->resize(700, 420, $allow_enlarge = true);
        $image->save($this->getTargetDirectory().'/medium/'.$fileName);

        // Large
        $image = new ImageResize($this->getTargetDirectory().'/'.$fileName);
        $image->resizeToBestFit(1200, 800, $allow_enlarge = true);
        $image->save($this->getTargetDirectory().'/large/'.$fileName);

        // Full
        $fileSystem->rename($this->getTargetDirectory().'/'.$fileName, $this->getTargetDirectory().'/full/'.$fileName);

        return $fileName;
    }

    public function remove(string $fileName): void
    {
        $fileSystem = new Filesystem();

        $folders = [
            '/small/',
            '/medium/',
            '/full/',
            '/large/',
        ];

        foreach ($folders as $folder) {
            $fileSystem->remove($this->getTargetDirectory().$folder.$fileName);
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
