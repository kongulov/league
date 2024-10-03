<?php

namespace Controller;

use Application\GameService;
use Domain\Game;
use Infrastructure\GameRepository;
use Infrastructure\MatchRepository;
use Infrastructure\TeamRepository;
use Infrastructure\LeagueStatsRepository;
use Domain\Team;

class GameController
{
    private $teamRepo;
    private $gameService;
    private $gameRepo;
    private $matchRepo;
    private $leagueStatsRepo;

    public function __construct(
        TeamRepository $teamRepo,
        MatchRepository $matchRepo,
        LeagueStatsRepository $leagueStatsRepo,
        GameRepository $gameRepo
    )
    {
        $this->teamRepo = $teamRepo;
        $this->matchRepo = $matchRepo;
        $this->leagueStatsRepo = $leagueStatsRepo;
        $this->gameRepo = $gameRepo;
        $this->gameService = new GameService($this->gameRepo, $this->leagueStatsRepo);
    }

    public function startNewGameAction()
    {
        $this->matchRepo->deleteAllMatches();
        $this->leagueStatsRepo->deleteAllStats();
        $teams = $this->teamRepo->getAllTeams();
        $this->gameService->generateWeeklyMatches($teams);
        $this->leagueStatsRepo->initStatsForTeams($teams);

        echo json_encode([
            'success' => true
        ]);
    }

    public function getNextMatchesAction()
    {
        $week = $this->matchRepo->getNextWeek();
        $matches = $this->matchRepo->getMatchesForWeek($week);

        echo json_encode($matches);
    }

    public function getCurrentWeekResultsAction()
    {
        $week = $this->matchRepo->getCurrentWeek();
        $matches = $this->matchRepo->getMatchesForWeek($week);

        echo json_encode($matches);
    }

    public function nextWeekAction()
    {
        $week = $this->matchRepo->getNextWeek();
        $matches = $this->matchRepo->getMatchesForWeek($week);

        if (empty($matches)) {
            echo json_encode(['success' => false]);
            return;
        }

        foreach ($matches as $match) {
            $homeTeam = new Team($match['home_team_id'], $match['home_team'], $match['home_team_strength']);
            $awayTeam = new Team($match['away_team_id'], $match['away_team'], $match['away_team_strength']);
            $game = new Game($homeTeam, $awayTeam, $week);
            $game->simulate();
            $this->matchRepo->updateMatch($game, $week);
            $this->gameService->updateLeagueStats($homeTeam, $awayTeam, $game->getResult()['home_team']['goals'], $game->getResult()['away_team']['goals']);
        }

        echo json_encode(['success' => true]);
    }


    public function predictChampionshipAction()
    {
        $return = $this->gameService->getWinProbForAllTeams();

        echo json_encode($return);
    }
}
