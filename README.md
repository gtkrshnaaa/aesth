# **Aesth Framework - Documentation**

## **Description**
Aesth is a simple PHP framework that follows the MVC (Model-View-Controller) pattern with easy-to-use routing. This framework allows for the development of web applications with clear and organized structure.

---

## **Directory Structure**
```
aesthframework/
├── app/
│   ├── controllers/
│   │   └── HomeController.php
│   └── views/
│       └── home.php
├── core/
│   ├── Render.php
│   └── Router.php
├── public/
│   └── index.php
└── routes/
    └── web.php
```

---

## **File & Directory Explanation**

### **1. app/controllers/HomeController.php**
This file contains the controller responsible for handling the logic of the homepage (`HomeController`). The controller calls the `Render::view` method to display the view.

```php
require_once __DIR__ . '/../../core/Render.php';

class HomeController {
    public static function index() {
        Render::view('home', ['title' => 'Welcome to My Mini Framework']);
    }
}
```

### **2. app/views/home.php**
This file is the view that will be rendered by the controller. It displays the homepage content, using the title passed from the controller.

```php
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

### **3. core/Render.php**
This file is responsible for rendering views. The `view()` method is used to extract data and load the appropriate view file.

```php
class Render {
    public static function view($viewName, $data = []) {
        extract($data);
        require_once __DIR__ . '/../app/views/' . $viewName . '.php';
    }
}
```

### **4. core/Router.php**
This file handles routing within the application. The `Router` class manages URL requests and associates them with the appropriate controller and action.

```php
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

### **5. public/index.php**
This file serves as the entry point for the application. All requests are routed through this file, which runs the routing system and calls the appropriate controller.

```php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../routes/web.php'; // Import routes from web.php

$router->run(); // Run the routing system
```

### **6. routes/web.php**
This file defines the application's routes. Here, you define which URL should be handled by which controller/action.

```php
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';

$router = new Router();

$router->get('/', function() {HomeController::index();});

```

---
