<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/core/Router.php';

$router = new Router();

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/api.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];

$requestUri = $_SERVER['REQUEST_URI'];

$requestUri = str_replace('/public/index.php', '', $requestUri);

$requestUri = parse_url($requestUri, PHP_URL_PATH);

if ($requestUri === '') {
    $requestUri = '/';
}

$response = $router->dispatch($requestUri, $requestMethod);

header('Content-Type: application/json');
echo json_encode($response);