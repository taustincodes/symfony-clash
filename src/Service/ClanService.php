<?php

namespace App\Service;

use App\Service\ClashAPIService;

class ClanService
{
    public function __construct(ClashAPIService $clashAPIService)
    {
        $this->clashAPIService = $clashAPIService;
    }

    public function getClan(string $clanTag)
    {
        $endpoint = "/clans/$clanTag/members";
        $clanMembers = $this->clashAPIService->getClashRoyaleData($endpoint);

        return $clanMembers;
    }
}
