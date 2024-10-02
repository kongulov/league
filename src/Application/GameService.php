<?php

namespace Application;

use Domain\Game;
use Domain\Team;
use Infrastructure\GameRepository;
use Infrastructure\LeagueStatsRepository;

class GameService
{
    private $gameRepo;
    private $leagueStatsRepo;

    public function __construct(GameRepository $gameRepo, LeagueStatsRepository $leagueStatsRepo)
    {
        $this->gameRepo = $gameRepo;
        $this->leagueStatsRepo = $leagueStatsRepo;
    }
    public function generateWeeklyMatches(array $teams): void
    {
        $teamsCount = count($teams);

        $week = 1;
        for ($i = 0; $i < $teamsCount; $i++) {
            for ($j = $i + 1; $j < $teamsCount; $j++) {
                $home = $teams[$i];
                $away = $teams[$j];

                $game = new Game($home, $away, $week);
                $this->gameRepo->saveGame($game);
                $week++;
            }
        }
    }

    public function updateLeagueStats(Team $homeTeam, Team $awayTeam, int $homeGoals, int $awayGoals, $leagueStatsRepo)
    {
        $homeStats = $leagueStatsRepo->getStatsForTeam($homeTeam->getId());
        $awayStats = $leagueStatsRepo->getStatsForTeam($awayTeam->getId());

        if ($homeGoals > $awayGoals) {
            $homeStats->addWin();
            $awayStats->addLoss();
        } elseif ($homeGoals < $awayGoals) {
            $awayStats->addWin();
            $homeStats->addLoss();
        } else {
            $homeStats->addDraw();
            $awayStats->addDraw();
        }

        $homeStats->updateGoalDifference($homeGoals - $awayGoals);
        $awayStats->updateGoalDifference($awayGoals - $homeGoals);

        $leagueStatsRepo->saveUpdatedStats($homeStats);
        $leagueStatsRepo->saveUpdatedStats($awayStats);
    }

    public function getWinProbForAllTeams(): array
    {
        $allStats = $this->leagueStatsRepo->getAllTeamsStats();

        $winWeight = 3;
        $drawWeight = 1;
        $lossWeight = -1;
        $goalDifferenceWeight = 0.5;

        $teamScores = [];

        foreach ($allStats as $teamStats) {
            $teamScore = max(0, ($teamStats['wins'] * $winWeight) +
                ($teamStats['draws'] * $drawWeight) +
                ($teamStats['losses'] * $lossWeight) +
                ($teamStats['goal_difference'] * $goalDifferenceWeight));

            $teamScores[$teamStats['name']] = $teamScore;
        }

        $totalScore = array_sum($teamScores);

        if ($totalScore == 0) {
            return array_map(function() {
                return 0;
            }, $teamScores);
        }

        $winProbabilities = [];
        foreach ($teamScores as $teamName => $teamScore) {
            $winProbabilities[$teamName] = round(($teamScore / $totalScore) * 100, 2);
        }

        arsort($winProbabilities);

        return $winProbabilities;
    }

}
