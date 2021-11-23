<?php

namespace App\Service;

use App\Service\ClashAPIService;

class CardService
{
    public $clashAPIService;

    public function __construct(ClashAPIService $clashAPIService)
    {
        $this->clashAPIService = $clashAPIService;
    }

    public function getCards()
    {
        $endpoint = "/cards";
        $cards = $this->clashAPIService->getClashRoyaleData($endpoint);

        return $cards;
    }

    public function getCardByName($name)
    {
        $cards = $this->getCards();
        $cards = $cards['items'];

        foreach ($cards as $card) {
            if ($card['name'] == $name) {
                $selectedCard = $card;
            }
        }

        if (!$selectedCard) {
            return null;
        } else {
            return $selectedCard;
        }
    }
}
