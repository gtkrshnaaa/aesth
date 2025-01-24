<?php

// core/Router.php

class Router {
    private $routes = [];

    // Menambahkan route GET
    public function get($url, $callback) {
        $this->routes['GET'][$url] = $callback;
    }

    // Menjalankan route yang sesuai
    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        // Memeriksa apakah route ada di array
        if (isset($this->routes[$method][$url])) {
            call_user_func($this->routes[$method][$url]);
        } else {
            echo '404 Not Found';
        }
    }
}
