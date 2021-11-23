<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\CardService;
use App\Service\PlayerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;


class PlayerController extends AbstractController
{
    public $cardService;
    public $playerService;

    public function __construct(CardService $cardService, PlayerService $playerService)
    {
        $this->cardService = $cardService;
        $this->playerService = $playerService;
    }

    /**
     * @Route("/player", name="player", methods="GET")
     */
    public function index(Request $request)
    {
        $id = $request->query->get("playerId");
        $playerId = "%23" . $id;

        //Account progress
        $player = $this->playerService->getPlayer($playerId);
        $accountProgress = $this->getAccountProgress($player);


        //Battle graphs
        $battleLog = $this->playerService->getBattleLog($playerId);
        $trophyLog = [];
        $crownLog = [
            "1" => 0,
            "2" => 0,
            "3" => 0
        ];

        //Linegraph
        foreach ($battleLog as $battle) {
            if ($battle['type'] == 'PvP') {
                array_push($trophyLog, ($battle['team'][0]['startingTrophies'] + $battle['team'][0]['trophyChange']));
                if ($battle['team'][0]['crowns'] > $battle['opponent'][0]['crowns']) {
                    $crownLog[$battle['team'][0]['crowns']]++;
                }
            }
        }

        $graph = [];
        $graph['y'] = implode(', ', array_values($trophyLog));
        $graph['x'] = implode(', ', array_keys($trophyLog));
        $crownLog = implode(', ', array_values($crownLog));

        //Most lost cards
        $mostLostCards = $this->playerService->getMostLostCards($playerId);
        foreach ($mostLostCards as &$card) {
            
            $cardDetails = $this->cardService->getCardByName($card['name']);
            $card += ['details' => $cardDetails];
        }










        //Render
        return $this->render('player2.html.twig', [
            "page" => "Player profile",
            'player' => $player,
            'mostLostCards' => $mostLostCards,
            'accountProgress' => $accountProgress,
            'graph' => $graph,
            'crownLog' => $crownLog
        ]);
    }

    public function getAccountProgress($player): Array
    {
        $cards = $player['cards'];
        $totalCards = 106;
        $percent = 1/$totalCards*100;
        $maxLevel = 14;
        $accountProgress = [];

        foreach ($cards as $card) {
            $level = $maxLevel - ($card['maxLevel'] - $card['level']);

            if (!isset($accountProgress[$level])) {
                $accountProgress[$level] = $percent;
            } else {
                $accountProgress[$level] += $percent;
            }
        }
        krsort($accountProgress);

        return $accountProgress;
    }
}