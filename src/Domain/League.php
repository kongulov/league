<?php

namespace Domain;

class League
{
    private ?int $id;
    private int $team_id;
    private int $points;
    private int $wins;
    private int $draws;
    private int $losses;
    private int $goal_difference;

    public function __construct(?int $id, int $team_id, int $points, int $wins, int $draws, int $losses, int $goal_difference)
    {
        $this->id = $id;
        $this->team_id = $team_id;
        $this->points = $points;
        $this->wins = $wins;
        $this->draws = $draws;
        $this->losses = $losses;
        $this->goal_difference = $goal_difference;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTeamId(): int
    {
        return $this->team_id;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getWins(): int
    {
        return $this->wins;
    }

    public function getDraws(): int
    {
        return $this->draws;
    }

    public function getLosses(): int
    {
        return $this->losses;
    }

    public function getGoalDifference(): int
    {
        return $this->goal_difference;
    }

    public function addWin(): void
    {
        $this->wins++;
        $this->points += 3;
    }

    public function addLoss(): void
    {
        $this->losses++;
    }

    public function addDraw(): void
    {
        $this->draws++;
        $this->points++;
    }

    public function updateGoalDifference(int $goals): void
    {
        $this->goal_difference += $goals;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'team_id' => $this->team_id,
            'points' => $this->points,
            'wins' => $this->wins,
            'draws' => $this->draws,
            'losses' => $this->losses,
            'goal_difference' => $this->goal_difference
        ];
    }

    public function getDrawProb(League $opponent): float
    {
        $total = $this->points + $opponent->getPoints();
        $diff = $this->points - $opponent->getPoints();
        $prob = 1 / (1 + pow(10, $diff / 600));

        return round($prob, 2);
    }
}
