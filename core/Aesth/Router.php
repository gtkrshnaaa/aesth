<?php

// /core/Aesth/Router.php

class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }

    public function addRoute($method, $path, $callback) {
        $this->routes[$method][$path] = $callback;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            call_user_func($callback);
        } else {
            echo "404 Not Found";
        }
    }
}