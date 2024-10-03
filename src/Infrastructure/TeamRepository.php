<?php

namespace Infrastructure;

use Domain\Team;

class TeamRepository
{
    private $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getPDO();
    }

    public function getAllTeams(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM teams');
        $teams = [];

        while ($row = $stmt->fetch()) {
            $teams[] = new Team($row['id'], $row['name'], (int)$row['strength']);
        }

        return $teams;
    }

    public function saveTeam(Team $team): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO teams (name, strength) VALUES (:name, :strength)');
        $stmt->execute([
            'name' => $team->getName(),
            'strength' => $team->getStrength()
        ]);
    }

    public function deleteTeam(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM teams WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
