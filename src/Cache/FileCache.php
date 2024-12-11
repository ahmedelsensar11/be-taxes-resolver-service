<?php
// src/Cache/FileCache.php

namespace App\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Cache\ItemInterface;

class FileCache
{
    private FilesystemAdapter $cache;
    private string $cacheDir;

    public function __construct(ParameterBagInterface $params)
    {
        $this->cacheDir = $params->get('kernel.cache_dir') . '/app';
        $this->cache = new FilesystemAdapter(
            namespace: 'app',
            defaultLifetime: 3600,
            directory: $this->cacheDir
        );
    }

    public function get(string $key, callable $callback = null, int $ttl = 3600)
    {
        return $this->cache->get($key, function (ItemInterface $item) use ($callback, $ttl) {
            $item->expiresAfter($ttl);
            return $callback ? $callback() : null;
        });
    }

    public function set(string $key, $value, int $ttl = 3600): bool
    {
        $item = $this->cache->getItem($key);
        $item->set($value);
        $item->expiresAfter($ttl);

        return $this->cache->save($item);
    }

    public function delete(string $key): bool
    {
        return $this->cache->deleteItem($key);
    }

    public function clear(): bool
    {
        return $this->cache->clear();
    }

    public function has(string $key): bool
    {
        return $this->cache->hasItem($key);
    }
}