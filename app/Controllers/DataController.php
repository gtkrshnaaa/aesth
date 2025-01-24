<?php
// /app/Controllers/DataController.php

require_once __DIR__ . '/../Models/Data.php';
require_once __DIR__ . '/../../core/Security/CSRF.php';
require_once __DIR__ . '/../../core/Security/XSS.php';
require_once __DIR__ . '/../../core/Aesth/Render.php';


class DataController {

    public function index() {
        $dataItems = Data::getAll();
        return Render::render('data/index.php', ['dataItems' => $dataItems]);
    }

    public function create() {
        $csrfToken = CSRF::generateToken();
        return Render::render('data/create.php', ['csrf_token' => $csrfToken]);
    }

    public function store() {
        if (!CSRF::validateToken($_POST['csrf_token'])) {
            die('CSRF token validation failed.');
        }

        $name = XSS::sanitize($_POST['name']);
        Data::create($name);
        header('Location: /data');
    }

    public function edit($id) {
        $dataItem = Data::getById($id);
        $csrfToken = CSRF::generateToken();
        return Render::render('data/edit.php', ['dataItem' => $dataItem, 'csrf_token' => $csrfToken]);
    }

    public function update($id) {
        if (!CSRF::validateToken($_POST['csrf_token'])) {
            die('CSRF token validation failed.');
        }

        $name = XSS::sanitize($_POST['name']);
        Data::update($id, $name);
        header('Location: /data');
    }

    public function delete($id) {
        Data::delete($id);
        header('Location: /data');
    }

}