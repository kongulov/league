<?php

namespace tests\Application;

use Application\GameService;
use Domain\Game;
use Domain\League;
use Domain\Team;
use Infrastructure\GameRepository;
use Infrastructure\LeagueStatsRepository;
use PHPUnit\Framework\TestCase;

class GameServiceTest extends TestCase
{
    private $gameService;
    private $gameRepo;
    private $leagueStatsRepo;

    protected function setUp(): void
    {
        $this->gameRepo = $this->createMock(GameRepository::class);
        $this->leagueStatsRepo = $this->createMock(LeagueStatsRepository::class);

        $this->gameService = new GameService($this->gameRepo, $this->leagueStatsRepo);
    }

    // generateWeeklyMatches test
    public function testGenerateWeeklyMatches()
    {
        $team1 = new Team(1, 'Real Madrid', 5);
        $team2 = new Team(2, 'Barcelona', 5);
        $team3 = new Team(3, 'Liverpool', 4);
        $team4 = new Team(4, 'Manchester United', 3);

        $teams = [$team1, $team2, $team3, $team4];

        $this->gameRepo->expects($this->exactly(6))
            ->method('saveGame')
            ->with($this->isInstanceOf(Game::class));

        $this->gameService->generateWeeklyMatches($teams);
    }

    public function testUpdateLeagueStats()
    {
        $homeTeam = new Team(1, 'Real Madrid', 5);
        $awayTeam = new Team(2, 'Barcelona', 5);

        $homeStats = $this->createMock(League::class);
        $awayStats = $this->createMock(League::class);

        $this->leagueStatsRepo->method('getStatsForTeam')
            ->willReturnMap([
                [$homeTeam->getId(), $homeStats],
                [$awayTeam->getId(), $awayStats],
            ]);

        $homeStats->expects($this->once())
            ->method('addWin');
        $awayStats->expects($this->once())
            ->method('addLoss');

        $this->leagueStatsRepo->expects($this->exactly(2))
            ->method('saveUpdatedStats');

        $this->gameService->updateLeagueStats($homeTeam, $awayTeam, 2, 1, $this->leagueStatsRepo);
    }

    public function testGetWinProbForAllTeams()
    {
        $this->leagueStatsRepo->method('getAllTeamsStats')
            ->willReturn([
                ['name' => 'Real Madrid', 'wins' => 5, 'draws' => 0, 'losses' => 0, 'goal_difference' => 20],
                ['name' => 'Barcelona', 'wins' => 4, 'draws' => 1, 'losses' => 1, 'goal_difference' => 10],
                ['name' => 'Liverpool', 'wins' => 4, 'draws' => 1, 'losses' => 1, 'goal_difference' => 15],
                ['name' => 'Manchester United', 'wins' => 3, 'draws' => 0, 'losses' => 3, 'goal_difference' => 5],
            ]);

        $winProb = $this->gameService->getWinProbForAllTeams();

        $this->assertEquals([
            'Real Madrid' => 35.71,
            'Barcelona' => 24.29,
            'Liverpool' => 27.86,
            'Manchester United' => 12.14,
        ], $winProb);
    }
}
