<?php
// db/migrations/migrateup.php

require_once __DIR__ . '/../connection.php';

class MigrateUp {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function up() {
        $sql = "
        CREATE TABLE IF NOT EXISTS data (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            value VARCHAR(255) NOT NULL
        );
        ";

        try {
            $this->conn->exec($sql);
            echo "Migration up successful: 'data' table created.\n";
        } catch (PDOException $e) {
            echo "Error during migration: " . $e->getMessage() . "\n";
        }
    }
}

// Create a new instance of the Database connection
$db = new Database();
$conn = $db->connect();

// Run the migration
$migration = new MigrateUp($conn);
$migration->up();
