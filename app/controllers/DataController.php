<?php
// app/controllers/DataController.php

require_once __DIR__ . '/../../app/models/Data.php';
require_once __DIR__ . '/../../core/Render.php';

class DataController {

    public function index() {
        $dataModel = new Data();
        $data = $dataModel->getAll();
        Render::view('data/index', ['data' => $data]);
    }

    public function create() {
        Render::view('data/create');
    }

    public function store() {
        if ($_POST) {
            $name = $_POST['name'];
            $value = $_POST['value'];
            $dataModel = new Data();
            $dataModel->store($name, $value);
            header("Location: /data");
        }
    }

    public function edit($id) {
        $dataModel = new Data();
        $data = $dataModel->getById($id);
        Render::view('data/edit', ['data' => $data]);
    }

    public function update($id) {
        if ($_POST) {
            $name = $_POST['name'];
            $value = $_POST['value'];
            $dataModel = new Data();
            $dataModel->update($id, $name, $value);
            header("Location: /data");
        }
    }

    public function delete($id) {
        $dataModel = new Data();
        $dataModel->delete($id);
        header("Location: /data");
    }
}
