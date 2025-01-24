<?php

// routes/web.php

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';  // Menambahkan ini

// Membuat instance Router
$router = new Router();

// Definisikan routes
$router->get('/', function() {
    // Panggil controller dan action untuk homepage
    HomeController::index();
});
