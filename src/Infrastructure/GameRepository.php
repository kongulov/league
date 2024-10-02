<?php

namespace Infrastructure;

use Domain\Game;

class GameRepository
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getPDO();
    }

    public function saveGame(Game $game): void
    {
        $result = $game->getResult();

        $stmt = $this->pdo->prepare('
            INSERT INTO matches (home_team_id, away_team_id, home_team_goals, away_team_goals, week, is_finished) 
            VALUES (:home_team, :away_team, :home_goals, :away_goals, :week, :is_finished)
        ');

        $stmt->execute([
            'home_team' => $result['home_team']['id'],
            'away_team' => $result['away_team']['id'],
            'home_goals' => $result['home_team']['goals'],
            'away_goals' => $result['away_team']['goals'],
            'week' => $result['week'],
            'is_finished' => $result['is_finished']
        ]);
    }
}
