<?php

declare(strict_types=1);

namespace App\Utils;

use Random\RandomException;

final class TokenGenerator
{
    /**
     * @throws RandomException
     */
    public function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
