<?php
// app/Controllers/WelcomeController.php

require_once __DIR__ . '/../../core/Aesth/Render.php';


class WelcomeController {
    public function index() {
        return Render::render('welcome.php');
    }
}
