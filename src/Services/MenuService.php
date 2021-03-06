<?php

namespace App\Services;

class MenuService
{
    private $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    public function getCardMenu()
    {
        return [
            'cards' => [
                'label' => 'Карты',
                'route' => 'card_index',
                'badge' => $this->cardService->getCardCount(),
                'icon' => 'far fa-copy',
                'submenus' => false,
            ],
            'card_parts' => [
                'label' => 'Части карт',
                'id' => 'card_parts',
                'icon' => 'far fa-file-alt',
                'submenus' => [
                    'artists' => [
                        'label' => 'Художники',
                        'route' => 'artist_index',
                        'badge' => $this->cardService->getArtistCount(),
                        'icon' => '',
                    ],
                    'editions' => [
                        'label' => 'Выпуски',
                        'route' => 'edition_index',
                        'badge' => $this->cardService->getEditionCount(),
                        'icon' => '',
                    ],
                    'elements' => [
                        'label' => 'Стихии',
                        'route' => 'element_index',
                        'badge' => $this->cardService->getElementCount(),
                        'icon' => '',
                    ],
                    'liquids' => [
                        'label' => 'Жидкости',
                        'route' => 'liquid_index',
                        'badge' => $this->cardService->getLiquidCount(),
                        'icon' => '',
                    ],
                    'rarities' => [
                        'label' => 'Редкости',
                        'route' => 'rarity_index',
                        'badge' => $this->cardService->getRarityCount(),
                        'icon' => '',
                    ],
                    'subtypes' => [
                        'label' => 'Подтипы',
                        'route' => 'subtype_index',
                        'badge' => $this->cardService->getSubtypeCount(),
                        'icon' => '',
                    ],
                    'supertypes' => [
                        'label' => 'Супертипы',
                        'route' => 'supertype_index',
                        'badge' => $this->cardService->getSupertypeCount(),
                        'icon' => '',
                    ],
                    'types' => [
                        'label' => 'Типы',
                        'route' => 'type_index',
                        'badge' => $this->cardService->getTypeCount(),
                        'icon' => '',
                    ],
                ]
            ]
        ];
    }
}
