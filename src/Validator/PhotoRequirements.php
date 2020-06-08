<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class PhotoRequirements extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new NotBlank([
                'message' => 'Please select a file to upload',
            ]),
            new File([
                'maxSize' => '12M',
                'mimeTypes' => [
                    'image/*',
                ],
            ]),
        ];
    }
}
