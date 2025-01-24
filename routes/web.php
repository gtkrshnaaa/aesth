<?php

// routes/web.php

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';

$router = new Router();

$router->get('/', function() {HomeController::index();});
