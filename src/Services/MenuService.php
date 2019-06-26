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
                'route' => 'artist_index',
                'badge' => 2,
            ],
        ];
    }
}
