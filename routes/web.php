<?php

// routes/web.php

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/DataController.php';

$router = new Router();

$router->get('/', function() { (new HomeController())->index(); });
$router->get('/data', function() { (new DataController())->index(); });
$router->get('/data/create', function() { (new DataController())->create(); });
$router->post('/data/create', function() { (new DataController())->store(); });
$router->get('/data/edit/{id}', function($id) { (new DataController())->edit($id); });
$router->post('/data/edit/{id}', function($id) { (new DataController())->update($id); });
$router->get('/data/delete/{id}', function($id) { (new DataController())->delete($id); });
