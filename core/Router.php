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
