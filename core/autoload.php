<?php
// /core/autoload.php
require __DIR__ . '/../db/connection.php';  // Add this require for DB connection
require __DIR__ . '/../app/Models/Data.php';
require __DIR__ . '/../core/Security/CSRF.php';
require __DIR__ . '/../core/Security/XSS.php';
require __DIR__ . '/../core/Aesth/Router.php';
require __DIR__ . '/../app/Controllers/DataController.php';
require __DIR__ . '/../app/Routes/web.php';