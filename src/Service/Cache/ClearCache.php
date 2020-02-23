<?php

declare(strict_types=1);

namespace App\Service\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

trait ClearCache
{
    public function clearCache(string $key): void
    {
        $cache = new FilesystemAdapter();
        $cache->delete($key);
    }
}
