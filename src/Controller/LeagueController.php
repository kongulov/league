<?php

namespace Controller;


use Infrastructure\LeagueStatsRepository;

class LeagueController
{
    private $leagueStatsRepo;

    public function __construct(LeagueStatsRepository $leagueStatsRepo)
    {
        $this->leagueStatsRepo = $leagueStatsRepo;
    }

    public function getLeagueTableAction()
    {
        $leagueTable = $this->leagueStatsRepo->getLeagueTable();

        echo json_encode($leagueTable);
    }
}
