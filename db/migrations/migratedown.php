<?php
// db/migrations/migratedown.php

require_once __DIR__ . '/../connection.php';


class MigrateDown {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function down() {
        $sql = "DROP TABLE IF EXISTS data;";

        try {
            $this->conn->exec($sql);
            echo "Migration down successful: 'data' table dropped.\n";
        } catch (PDOException $e) {
            echo "Error during migration: " . $e->getMessage() . "\n";
        }
    }
}

// Create a new instance of the Database connection
$db = new Database();
$conn = $db->connect();

// Run the migration
$migration = new MigrateDown($conn);
$migration->down();
