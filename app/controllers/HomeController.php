<?php

// app/controllers/HomeController.php

require_once __DIR__ . '/../../core/Render.php';

class HomeController {
    public function index() {
        Render::view('home', ['title' => 'Welcome to My Mini Framework']);
    }
}
