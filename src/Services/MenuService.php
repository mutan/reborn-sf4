<?php

namespace App\Services;

class MenuService
{
    private $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    public function getMenu()
    {
        return [
            'artists' => [
                'label' => 'Художники',
                'route' => 'artist_index',
                'badge' => $this->cardService->getArtistCount(),
            ],
            'editions' => [
                'label' => 'Выпуски',
                'route' => 'edition_index',
                'badge' => $this->cardService->getEditionCount(),
            ],
            'elements' => [
                'label' => 'Стихии',
                'route' => 'element_index',
                'badge' => $this->cardService->getElementCount(),
            ],
            'liquids' => [
                'label' => 'Жидкости',
                'route' => 'liquid_index',
                'badge' => $this->cardService->getLiquidCount(),
            ],
            'rarities' => [
                'label' => 'Редкости',
                'route' => 'rarity_index',
                'badge' => $this->cardService->getRarityCount(),
            ],
            'subtypes' => [
                'label' => 'Подтипы',
                'route' => 'subtype_index',
                'badge' => $this->cardService->getSubtypeCount(),
            ],
            'supertypes' => [
                'label' => 'Супертипы',
                'route' => 'supertype_index',
                'badge' => $this->cardService->getSupertypeCount(),
            ],
            'types' => [
                'label' => 'Типы',
                'route' => 'type_index',
                'badge' => $this->cardService->getTypeCount(),
            ],
        ];
    }
}
