<?php

namespace Domain;

class Game
{
    private Team $homeTeam;
    private Team $awayTeam;
    private int $homeGoals = 0;
    private int $awayGoals = 0;
    private int $week;
    private int $isFinished = 0;

    public function __construct(Team $homeTeam, Team $awayTeam, int $week = 1)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->week = $week;
    }

    public function simulate()
    {
        $this->homeGoals = rand(0, $this->homeTeam->getStrength());
        $this->awayGoals = rand(0, $this->awayTeam->getStrength());
    }

    // Получение результата игры
    public function getResult(): array
    {
        return [
            'home_team' => [
                'id' => $this->homeTeam->getId(),
                'name' => $this->homeTeam->getName(),
                'goals' => $this->homeGoals
            ],
            'away_team' => [
                'id' => $this->awayTeam->getId(),
                'name' => $this->awayTeam->getName(),
                'goals' => $this->awayGoals
            ],
            'week' => $this->week,
            'is_finished' => $this->isFinished
        ];
    }
}
