<?php

namespace App\Service;

use App\Service\ClashAPIService;

class PlayerService
{
    public $clashAPIService;

    public function __construct(ClashAPIService $clashAPIService)
    {
        $this->clashAPIService = $clashAPIService;
    }

    public function getPlayer(string $playerTag)
    {
        $endpoint = "/players/$playerTag";
        $player = $this->clashAPIService->getClashRoyaleData($endpoint);

        return $player;
    }

    public function getUpcomingChests(string $playerTag)
    {
        $endpoint = "/players/$playerTag/upcomingchests";
        $chests = $this->clashAPIService->getClashRoyaleData($endpoint);

        return $chests;

    }

    public function getBattleLog(string $playerTag)
    {
        $endpoint = "/players/$playerTag/battlelog";
        $battleLog = $this->clashAPIService->getClashRoyaleData($endpoint);

        return $battleLog;
    }

    public function getMostLostCards(string $playerTag)
    {
        $battles = $this->getBattleLog($playerTag);
        $cardLog = [];

        foreach ($battles as $battle) {
            $cards = $battle['opponent']['0']['cards'];

            foreach ($cards as $card) {
                $cardName = $card['name'];
                if (!array_key_exists($cardName, $cardLog)) {
                    $cardLog[$cardName] = 1;
                } else {
                    $cardLog[$cardName]++;
                }
            }
        }
        arsort($cardLog);
        $cardLog = array_slice($cardLog, 0, 3);
        $top3 = [];

        foreach ($cardLog as $name => $value) {
            $entry = [
                'name' => $name,
                'value' => $value
            ];

            array_push($top3, $entry);
        }
        
        return $top3;
    }
}