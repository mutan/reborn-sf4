<?php

namespace App\Services;

use App\Repository\ArtistRepository;
use App\Repository\CardRepository;
use App\Repository\EditionRepository;
use App\Repository\ElementRepository;
use App\Repository\Interfaces\CountableRepositoryInterface;
use App\Repository\LiquidRepository;
use App\Repository\RarityRepository;
use App\Repository\SubtypeRepository;
use App\Repository\SupertypeRepository;
use App\Repository\TypeRepository;
use Exception;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CardService
{
    const CACHE_KEY_CARD_COUNT = 'card_count';
    const CACHE_KEY_ARTIST_COUNT = 'artist_count';
    const CACHE_KEY_EDITION_COUNT = 'edition_count';
    const CACHE_KEY_ELEMENT_COUNT = 'element_count';
    const CACHE_KEY_LIQUID_COUNT = 'liquid_count';
    const CACHE_KEY_RARITY_COUNT = 'rarity_count';
    const CACHE_KEY_SUBTYPE_COUNT = 'subtype_count';
    const CACHE_KEY_SUPERTYPE_COUNT = 'supertype_count';
    const CACHE_KEY_TYPE_COUNT = 'type_count';

    protected $cache;
    private $artistRepository;
    private $cardRepository;
    private $editionRepository;
    private $elementRepository;
    private $liquidRepository;
    private $rarityRepository;
    private $subtypeRepository;
    private $supertypeRepository;
    private $typeRepository;
    private $parameterBag;

    public function __construct(
        AdapterInterface $cache,
        ArtistRepository $artistRepository,
        CardRepository $cardRepository,
        EditionRepository $editionRepository,
        ElementRepository $elementRepository,
        LiquidRepository $liquidRepository,
        RarityRepository $rarityRepository,
        SubtypeRepository $subtypeRepository,
        SupertypeRepository $supertypeRepository,
        TypeRepository $typeRepository,
        ParameterBagInterface $parameterBag
    )
    {
        $this->cache = $cache;
        $this->cardRepository = $cardRepository;
        $this->artistRepository = $artistRepository;
        $this->editionRepository = $editionRepository;
        $this->elementRepository = $elementRepository;
        $this->liquidRepository = $liquidRepository;
        $this->rarityRepository = $rarityRepository;
        $this->subtypeRepository = $subtypeRepository;
        $this->supertypeRepository = $supertypeRepository;
        $this->typeRepository = $typeRepository;
        $this->parameterBag = $parameterBag;
    }

    public function getCardCount()
    {
        return $this->getCachedItem(self::CACHE_KEY_CARD_COUNT, $this->cardRepository);
    }

    public function getArtistCount()
    {
        return $this->getCachedItem(self::CACHE_KEY_ARTIST_COUNT, $this->artistRepository);
    }

    public function getEditionCount()
    {
        return $this->getCachedItem(self::CACHE_KEY_EDITION_COUNT, $this->editionRepository);
    }

    public function getElementCount()
    {
        return $this->getCachedItem(self::CACHE_KEY_ELEMENT_COUNT, $this->elementRepository);
    }

    public function getLiquidCount()
    {
        return $this->getCachedItem(self::CACHE_KEY_LIQUID_COUNT, $this->liquidRepository);
    }

    public function getRarityCount()
    {
        return $this->getCachedItem(self::CACHE_KEY_RARITY_COUNT, $this->rarityRepository);
    }

    public function getSubtypeCount()
    {
        return $this->getCachedItem(self::CACHE_KEY_SUBTYPE_COUNT, $this->subtypeRepository);
    }

    public function getSupertypeCount()
    {
        return $this->getCachedItem(self::CACHE_KEY_SUPERTYPE_COUNT, $this->supertypeRepository);
    }

    public function getTypeCount()
    {
        return $this->getCachedItem(self::CACHE_KEY_TYPE_COUNT, $this->typeRepository);
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

    /**
     * Конвертирует строку из константы DATA_PATH в валидный путь для текущей ОС
     * @param string $path
     * @return string
     * @throws Exception
     */
    public function getSanitizedPath(string $path): string
    {
        $pieces = array_filter(explode('/', $path));
        $path = $this->parameterBag->get('kernel.project_dir') . DIRECTORY_SEPARATOR .
            implode(DIRECTORY_SEPARATOR, $pieces) . DIRECTORY_SEPARATOR;
        if (!file_exists($path)) {
            if (!mkdir($path, 0755, true)) {
                throw new Exception('Expired passports: cannot create data directory.');
            }
        }
        return $path;
    }

    public function getImageDir(): string
    {
        return '/images/';
    }

    public function getIconDir(): string
    {
        return '/icons/';
    }
}
