<?php
// /app/Models/Data.php
class Data {

    // Get all data
    public static function getAll() {
        $connection = DatabaseConnection::connect();
        $result = $connection->query('SELECT * FROM data');
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get data by ID
    public static function getById($id) {
        $connection = DatabaseConnection::connect();
        $stmt = $connection->prepare('SELECT * FROM data WHERE id = ?');
        $stmt->bind_param('i', $id); // Bind the ID parameter
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Create new data
    public static function create($name) {
        $connection = DatabaseConnection::connect();
        $stmt = $connection->prepare('INSERT INTO data (name) VALUES (?)');
        $stmt->bind_param('s', $name); // Bind the name parameter
        $stmt->execute();
    }

    // Update data
    public static function update($id, $name) {
        $connection = DatabaseConnection::connect();
        $stmt = $connection->prepare('UPDATE data SET name = ? WHERE id = ?');
        $stmt->bind_param('si', $name, $id); // Bind the name and ID parameters
        $stmt->execute();
    }

    // Delete data
    public static function delete($id) {
        $connection = DatabaseConnection::connect();
        $stmt = $connection->prepare('DELETE FROM data WHERE id = ?');
        $stmt->bind_param('i', $id); // Bind the ID parameter
        $stmt->execute();
    }
}