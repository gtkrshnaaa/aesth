<?php

// /core/Aesth/Render.php

class Render {
    public static function render($view, $data = []) {
        extract($data); // Extracting the data array into variables
        ob_start();
        require __DIR__ . '/../../Views/' . $view;
        return ob_get_clean();
    }
}
