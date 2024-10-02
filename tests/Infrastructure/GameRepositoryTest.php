<?php

namespace tests\Infrastructure;

use Application\GameService;
use Domain\Game;
use Domain\Team;
use Infrastructure\GameRepository;
use PHPUnit\Framework\TestCase;
use Infrastructure\LeagueStatsRepository;

class GameRepositoryTest extends TestCase
{
    private $leagueStatsRepo;

    private $gameService;
    private $gameRepo;

    protected function setUp(): void
    {
        $this->gameRepo = $this->createMock(GameRepository::class);
        $this->leagueStatsRepo = $this->createMock(LeagueStatsRepository::class);
        $this->gameService = new GameService($this->gameRepo, $this->leagueStatsRepo);
    }

    public function testSaveGame()
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
}
