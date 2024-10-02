<?php

namespace tests\Infrastructure;

use Application\GameService;
use Domain\Game;
use Domain\Team;
use Infrastructure\GameRepository;
use Infrastructure\MatchRepository;
use PHPUnit\Framework\TestCase;
use Infrastructure\LeagueStatsRepository;
class MatchRepositoryTest extends TestCase
{
    private $matchRepo;

    protected function setUp(): void
    {
        $this->matchRepo = $this->createMock(MatchRepository::class);
    }

    public function testUpdateMatch()
    {
        $team1 = new Team(1, 'Real Madrid', 5);
        $team2 = new Team(2, 'Barcelona', 5);
        $week = 1;

        $game = new Game($team1, $team2, $week);

        $this->matchRepo->expects($this->once())
            ->method('updateMatch')
            ->with($game);

        $this->matchRepo->updateMatch($game, 2);
    }

    public function testGetMatchesForWeek()
    {
        $week = 1;

        $this->matchRepo->expects($this->once())
            ->method('getMatchesForWeek')
            ->with($week);

        $this->matchRepo->getMatchesForWeek($week);
    }
}
