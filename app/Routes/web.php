<?php

// /app/Routes/web.php

require_once __DIR__ . '/../../core/Aesth/Router.php';

require_once __DIR__ . '/../Controllers/WelcomeController.php';
require_once __DIR__ . '/../Controllers/DataController.php';

$router = new Router();

$router->get('/', [WelcomeController::class, 'index']);

$router->get('/data', [new DataController(), 'index']);
$router->get('/data/create', [new DataController(), 'create']);
$router->post('/data', [new DataController(), 'store']);
$router->get('/data/{id}/edit', [new DataController(), 'edit']);
$router->post('/data/{id}/update', [new DataController(), 'update']);
$router->get('/data/{id}/delete', [new DataController(), 'delete']);

$router->run();
