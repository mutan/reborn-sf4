<?php

namespace App\Services;

use App\Repository\ArtistRepository;
use App\Repository\Interfaces\CountableRepositoryInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class CardService
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

    public function getArtistCount()
    {
        return $this->getCachedItem(self::CACHE_KEY_ARTIST_COUNT, $this->artistRepository);
    }

    private function getCachedItem(string $key, CountableRepositoryInterface $repository)
    {
        $item = $this->cache->getItem($key);
        if (!$item->isHit()) {
            $item->set($repository->getCount());
            $this->cache->save($item);
        }
        return $item->get();
    }
}
