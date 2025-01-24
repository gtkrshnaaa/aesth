<?php

// core/Render.php

class Render {
    public static function view($viewName, $data = []) {
        // Memasukkan data ke dalam view
        extract($data);
        require_once __DIR__ . '/../app/views/' . $viewName . '.php';

    }
}
