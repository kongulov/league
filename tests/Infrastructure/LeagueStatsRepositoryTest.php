<?php

namespace tests\Infrastructure;

use Domain\Team;
use PHPUnit\Framework\TestCase;
use Infrastructure\LeagueStatsRepository;
use Domain\League;

class LeagueStatsRepositoryTest extends TestCase
{
    private $leagueStatsRepo;

    protected function setUp(): void
    {
        $this->leagueStatsRepo = $this->createMock(LeagueStatsRepository::class);
    }

    public function testGetStatsForTeam()
    {
        $team1 = new Team(1, 'Real Madrid', 5);

        $this->leagueStatsRepo->expects($this->once())
            ->method('getStatsForTeam')
            ->with($team1->getId())
            ->willReturn(new League(1, 1, 0, 0, 0, 0, 0));

        $this->leagueStatsRepo->getStatsForTeam($team1->getId());
    }

    public function testSaveUpdatedStats()
    {
        $league = new League(1, 1, 0, 0, 0, 0, 0);

        $this->leagueStatsRepo->expects($this->once())
            ->method('saveUpdatedStats')
            ->with($league);

        $this->leagueStatsRepo->saveUpdatedStats($league);
    }

    public function testGetAllTeamsStats()
    {
        $this->leagueStatsRepo->expects($this->once())
            ->method('getAllTeamsStats')
            ->willReturn([]);

        $this->leagueStatsRepo->getAllTeamsStats();
    }

    public function testInitStatsForTeam()
    {
        $team1 = new Team(1, 'Real Madrid', 5);

        $this->leagueStatsRepo->expects($this->once())
            ->method('initStatsForTeam')
            ->with($team1->getId());

        $this->leagueStatsRepo->initStatsForTeam($team1->getId());
    }

    public function testGetLeagueTable()
    {
        $this->leagueStatsRepo->expects($this->once())
            ->method('getLeagueTable')
            ->willReturn([]);

        $this->leagueStatsRepo->getLeagueTable();
    }
}
