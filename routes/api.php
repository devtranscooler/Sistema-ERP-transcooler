<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/ProductController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/MediaController.php';

$router->get('/api/products', function () {
    return (new ProductController())->index($_GET);
});

$router->get('/api/media', function() {
    return (new MediaController())->index($_GET);
});

$router->post('/api/media', function() {
    return (new MediaController())->upload($_POST, $_FILES);
});

$router->delete('/api/media/{id}', function($id) {
    return (new MediaController())->delete((int) $id);
});