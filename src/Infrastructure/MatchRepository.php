<?php

namespace Infrastructure;

use Domain\Game;

class MatchRepository
{
    private $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getPDO();
    }

    public function updateMatch(Game $game, int $week): void
    {
        $result = $game->getResult();

        $stmt = $this->pdo->prepare('
            UPDATE matches 
            SET home_team_goals = :home_goals, away_team_goals = :away_goals, is_finished = 1
            WHERE home_team_id = :home_team_id AND away_team_id = :away_team_id AND week = :week
        ');
        $stmt->execute([
            'home_team_id' => $result['home_team']['id'],
            'away_team_id' => $result['away_team']['id'],
            'home_goals' => $result['home_team']['goals'],
            'away_goals' => $result['away_team']['goals'],
            'week' => $week
        ]);
    }
    public function getMatchesForWeek(int $week): array
    {
        $stmt = $this->pdo->prepare('
            SELECT m.id, m.home_team_goals, m.away_team_goals, m.week, 
            t1.name as home_team, t2.name as away_team, t1.id as home_team_id, t2.id as away_team_id, t1.strength as home_team_strength, t2.strength as away_team_strength
            FROM matches m
            JOIN teams t1 ON m.home_team_id = t1.id
            JOIN teams t2 ON m.away_team_id = t2.id
            WHERE m.week = :week
        ');
        $stmt->execute(['week' => $week]);
        return $stmt->fetchAll();
    }


    public function getAllMatches(): array
    {
        $stmt = $this->pdo->prepare('
            SELECT m.id, m.home_team_goals, m.away_team_goals, m.week, 
            t1.name as home_team, t2.name as away_team, t1.id as home_team_id, t2.id as away_team_id, t1.strength as home_team_strength, t2.strength as away_team_strength
            FROM matches m
            JOIN teams t1 ON m.home_team_id = t1.id
            JOIN teams t2 ON m.away_team_id = t2.id
        ');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getNextWeek(): int
    {
        $stmt = $this->pdo->query('SELECT MIN(week) FROM matches where is_finished = 0');

        return (int)$stmt->fetchColumn();
    }

    public function getCurrentWeek(): int
    {
        $stmt = $this->pdo->query('SELECT MAX(week) FROM matches where is_finished = 1');

        return (int)$stmt->fetchColumn();
    }

    public function deleteAllMatches(): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM matches');

        $stmt->execute();
    }
}
