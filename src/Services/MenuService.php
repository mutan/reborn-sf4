<?php

namespace App\Services;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class MenuService
{
    /** @var FilesystemAdapter */
    protected $cache;

    const MENU = [
        'artists' => [
            'label' => 'Художники',
            'route' => 'artist_index',
            'badge' => '',
        ]
    ];

    public function __construct(FilesystemAdapter $cache)
    {
        $this->cache = $cache;
    }

    public function getMenu()
    {
        $cache = new FilesystemAdapter();

        $value = $cache->get('my_cache_key', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            // ... do some HTTP request or heavy computations
            $computedValue = 'foobar';

            return $computedValue;
        });
    }

}