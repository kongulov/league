<?php

require '../vendor/autoload.php';

$request = $_SERVER['REQUEST_URI'];
$uri = parse_url($request, PHP_URL_PATH);
$uri = substr($uri, 1);
$uri = explode('/', $uri);

$controller = $uri[0] !== '' ? $uri[0] : 'index';
$action = $uri[1] ?? 'index';

$controller = 'Controller\\'.ucfirst($controller) . 'Controller';
$action = $action.'Action';

if (!class_exists($controller)) {
    http_response_code(404);
    echo 'Controller not found';
    exit;
}

$controller = new $controller();
if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo 'Action not found';
    exit;
}

$controller->$action();

/*

$teamController = new TeamController();
$gameController = new GameController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'start_new_game':
            $matches = $gameController->startNewGame();
            echo json_encode(['success' => true, 'matches' => $matches]);
            break;
        case 'next_week':
            $results = $gameController->playNextWeek();
            if ($results) {
                echo json_encode(['success' => true, 'results' => $results]);
            } else {
                echo json_encode(['error' => 'No more weeks']);
            }
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Unknown action']);
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'get_teams') {
        $teams = $teamController->getTeams();
        echo json_encode($teams);
    } elseif ($action === 'get_matches_for_week') {
        $matches = $gameController->getMatchesForCurrentWeek();
        echo json_encode($matches);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
    }
}*/
