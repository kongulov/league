<?php

namespace Infrastructure;

use Domain\League;

class LeagueStatsRepository
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getPDO();
    }

    public function getStatsForTeam(int $teamId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM league_stats WHERE team_id = :team_id');
        $stmt->execute(['team_id' => $teamId]);

        $league = $stmt->fetchObject();

        return new League($league->id, $league->team_id, $league->points, $league->wins, $league->draws, $league->losses, $league->goal_difference);
    }

    public function saveUpdatedStats($stats): void
    {
        $stmt = $this->pdo->prepare('UPDATE league_stats 
            SET points = :points, wins = :wins, draws = :draws, losses = :losses, goal_difference = :goal_difference 
            WHERE team_id = :team_id');
        $stmt->execute([
            'points' => $stats->getPoints(),
            'wins' => $stats->getWins(),
            'draws' => $stats->getDraws(),
            'losses' => $stats->getLosses(),
            'goal_difference' => $stats->getGoalDifference(),
            'team_id' => $stats->getTeamId()
        ]);
    }

    public function getAllTeamsStats(): array
    {
        $stmt = $this->pdo->query('
            SELECT t.name, ls.wins, ls.draws, ls.losses, ls.goal_difference
            FROM league_stats ls
            JOIN teams t ON ls.team_id = t.id
        ');

        return $stmt->fetchAll();
    }

    public function initStatsForTeams(array $teams): void
    {
        foreach ($teams as $team) {
            $this->initStatsForTeam($team->getId());
        }
    }

    public function initStatsForTeam(int $teamId): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO league_stats (team_id) VALUES (:team_id)');
        $stmt->execute(['team_id' => $teamId]);
    }

    public function getLeagueTable(): array
    {
        $stmt = $this->pdo->query('
            SELECT t.name, ls.points, ls.wins, ls.draws, ls.losses, ls.goal_difference
            FROM league_stats ls
            JOIN teams t ON ls.team_id = t.id
            ORDER BY ls.points DESC, ls.goal_difference DESC
        ');
        return $stmt->fetchAll();
    }

    public function deleteAllStats(): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM league_stats');
        $stmt->execute();
    }

    public function getStatsForTeams(array $teams): array
    {
        $teamIds = array_map(function ($team) {
            return $team->getId();
        }, $teams);

        $stmt = $this->pdo->prepare('SELECT * FROM league_stats WHERE team_id IN (' . implode(',', $teamIds) . ')');
        $stmt->execute();

        $leagues = $stmt->fetchAll();
        $stats = [];
        foreach ($leagues as $league) {
            $stats[$league['team_id']] = new League($league['id'], $league['team_id'], $league['points'], $league['wins'], $league['draws'], $league['losses'], $league['goal_difference']);
        }

        return $stats;
    }
}
