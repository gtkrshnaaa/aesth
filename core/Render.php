<?php

// core/Render.php

class Render {
    public static function view($viewName, $data = []) {
        extract($data);
        require_once __DIR__ . '/../app/views/' . $viewName . '.php';

    }
}
