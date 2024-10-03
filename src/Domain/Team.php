<?php

namespace Domain;

class Team
{
    private ?int $id;
    private string $name;
    private int $strength;

    public function __construct(?int $id, string $name, int $strength)
    {
        $this->id = $id;
        $this->name = $name;
        $this->strength = $strength;

        if (!$this->validateName()) {
            throw new \InvalidArgumentException('Team name is required');
        }

        if (!$this->validateStrength()) {
            throw new \InvalidArgumentException('Team strength must be between 1 and 5');
        }
    }

    // validate the team name
    public function validateName(): bool
    {
        return strlen($this->name) > 0;
    }

    // validate the team strength
    public function validateStrength(): bool
    {
        return $this->strength >= 1 && $this->strength <= 5;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStrength(): int
    {
        return $this->strength;
    }
}
