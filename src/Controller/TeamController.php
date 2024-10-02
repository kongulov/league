<?php

namespace Controller;

use Infrastructure\TeamRepository;
use Domain\Team;

class TeamController
{
    private $teamRepo;

    public function __construct()
    {
        $this->teamRepo = new TeamRepository();
    }

    public function indexAction()
    {
        include 'teams.html';
    }

    public function addTeamAction()
    {
        $name = $_POST['name'];
        $strength = (int)$_POST['strength'];

        $this->teamRepo->saveTeam(new Team(null, $name, $strength));
    }

    public function deleteTeamAction()
    {
        $teamId = (int)$_POST['id'];
        $this->teamRepo->deleteTeam($teamId);
    }

    public function getTeamsAction()
    {
        $teams = $this->teamRepo->getAllTeams();
        $return = array_map(function($team) {
            return [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'strength' => $team->getStrength()
            ];
        }, $teams);

        echo json_encode($return);
    }
}
