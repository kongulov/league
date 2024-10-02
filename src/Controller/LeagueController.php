<?php

namespace Controller;


use Infrastructure\LeagueStatsRepository;

class LeagueController
{
    private $leagueStatsRepo;

    public function __construct()
    {
        $this->leagueStatsRepo = new LeagueStatsRepository();
    }

    public function getLeagueTableAction()
    {
        $leagueTable = $this->leagueStatsRepo->getLeagueTable();
        echo json_encode($leagueTable);
    }
}
