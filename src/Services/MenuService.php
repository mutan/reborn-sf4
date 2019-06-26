<?php

namespace App\Services;

use App\Repository\ArtistRepository;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MenuService
{
    const CACHE_KEY_ARTIST_COUNT = 'artist_count';

    /** @var AdapterInterface */
    protected $cache;
    /** @var ArtistRepository */
    private $artistRepository;

    public function __construct(AdapterInterface $cache, ArtistRepository $artistRepository)
    {
        $this->cache = $cache;
        $this->artistRepository = $artistRepository;
    }

    public function getMenu()
    {
        return [
            'artists' => [
                'label' => 'Художники',
                'route' => 'artist_index',
                'badge' => $this->getArtistCount(),
            ],
            'editions' => [
                'label' => 'Выпуски',
                'route' => 'edition_index',
                'badge' => 2,
            ],
        ];
    }

    public function getArtistCount()
    {
        $item = $this->cache->getItem(self::CACHE_KEY_ARTIST_COUNT);
        if (!$item->isHit()) {
            $item->set($this->artistRepository->getCount());
            $this->cache->save($item);
        }
        return $item->get();
    }
}
