<?php
// /app/Routes/web.php
$router = new Router();

$router->get('/data', [new DataController(), 'index']);
$router->get('/data/create', [new DataController(), 'create']);
$router->post('/data', [new DataController(), 'store']);
$router->get('/data/{id}/edit', [new DataController(), 'edit']);
$router->post('/data/{id}/update', [new DataController(), 'update']);
$router->get('/data/{id}/delete', [new DataController(), 'delete']);

$router->run();