<?php

// public/index.php

// Aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include core classes



// Include route definitions
require_once __DIR__ . '/../routes/web.php';


// Jalankan routing
$router->run();

