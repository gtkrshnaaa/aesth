<?php

// index.php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start a session for CSRF token management
session_start();

// Define the root directory of the project
define('BASE_PATH', dirname(__DIR__));

// Include the routing setup
require_once __DIR__ . '/app/Routes/web.php';

// Run the router to handle the incoming request
$router->run();
