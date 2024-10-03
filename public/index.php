<?php

use Infrastructure\Container;

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

$container = new Container();
$controllerInstance = $container->get($controller);

if (!method_exists($controllerInstance, $action)) {
    http_response_code(404);
    echo 'Action not found';
    exit;
}

$controllerInstance->$action();