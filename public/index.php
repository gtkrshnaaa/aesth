<?php

// Start a session to enable CSRF token management
session_start();

// Define the root directory of the project
define('BASE_PATH', dirname(__DIR__));


// Initialize and run the router
$router = new Router();

// Register routes
require BASE_PATH . '/app/Routes/web.php';

// Run the router to handle the incoming request
$router->run();
