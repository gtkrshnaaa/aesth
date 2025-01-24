# Project File List

## tree.txt
```txt
.
├── app
│   ├── controllers
│   │   └── HomeController.php
│   └── views
│       └── home.php
├── core
│   ├── Render.php
│   └── Router.php
├── file_list.md
├── mdgenerator.php
├── public
│   └── index.php
├── README.md
├── routes
│   └── web.php
└── tree.txt

```

## public/index.php
```php
<?php

// public/index.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../routes/web.php';

$router->run();


```

## app/views/home.php
```php
<!-- app/views/home.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
</head>
<body>
    <h1><?= $title ?></h1>
</body>
</html>

```

## app/controllers/HomeController.php
```php
<?php

// app/controllers/HomeController.php

require_once __DIR__ . '/../../core/Render.php';


class HomeController {
    public static function index() {
        Render::view('home', ['title' => 'Welcome to My Mini Framework']);
    }
}

```

## core/Router.php
```php
<?php

// core/Router.php

class Router {
    private $routes = [];

    public function get($url, $callback) {
        $this->routes['GET'][$url] = $callback;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        if (isset($this->routes[$method][$url])) {
            call_user_func($this->routes[$method][$url]);
        } else {
            echo '404 Not Found';
        }
    }
}

```

## core/Render.php
```php
<?php

// core/Render.php

class Render {
    public static function view($viewName, $data = []) {
        extract($data);
        require_once __DIR__ . '/../app/views/' . $viewName . '.php';

    }
}

```

## .htaccess
```htaccess
RewriteEngine On
RewriteBase /

# Redirect all requests to index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

```

## routes/web.php
```php
<?php

// routes/web.php

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';

$router = new Router();

$router->get('/', function() {HomeController::index();});

```

