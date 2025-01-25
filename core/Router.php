<?php

// core/Router.php

class Router {
    private $routes = [];

    public function get($url, $callback) {
        $this->routes['GET'][$url] = $callback;
    }

    public function post($url, $callback) {
        $this->routes['POST'][$url] = $callback;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = rtrim($url, '/'); 
    
        if ($url === '') {
            $url = '/';
        }
        
        foreach ($this->routes[$method] as $route => $callback) {
            $routePattern = preg_replace('/{[a-zA-Z0-9_]+}/', '([a-zA-Z0-9_]+)', $route);
            if (preg_match('#^' . $routePattern . '$#', $url, $matches)) {
                array_shift($matches); 
                call_user_func_array($callback, $matches);
                return;
            }
        }
        
        echo '404 Not Found';
    }
    
}
