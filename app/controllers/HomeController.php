<?php

// app/controllers/HomeController.php

require_once __DIR__ . '/../../core/Render.php';


class HomeController {
    public static function index() {
        // Render halaman home.php dan kirimkan data
        Render::view('home', ['title' => 'Welcome to My Mini Framework']);
    }
}
