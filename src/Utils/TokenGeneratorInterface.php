<?php

declare(strict_types=1);

namespace App\Utils;

interface TokenGeneratorInterface
{
    public function generateToken(): string;
}
