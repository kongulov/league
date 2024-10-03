<?php

namespace Controller;

use Infrastructure\TeamRepository;
use Domain\Team;

class TeamController
{
    private $teamRepo;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepo = $teamRepository;
    }

    public function indexAction()
    {
        include 'teams.html';
    }

    public function addTeamAction()
    {
        try {
            $name = $_POST['name'];
            $strength = (int)$_POST['strength'];

            $this->teamRepo->saveTeam(new Team(null, $name, $strength));

            echo json_encode(['success' => true]);
        }
        catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function deleteTeamAction()
    {
        $teamId = (int)$_POST['id'];
        $this->teamRepo->deleteTeam($teamId);

        echo json_encode(['success' => true]);
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
