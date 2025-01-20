<?php
// /db/migrations/migrate_up.php
require __DIR__ . '/../connection.php';

$connection = DatabaseConnection::connect();

// SQL query to create the "data" table
$query = "CREATE TABLE IF NOT EXISTS data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($connection->query($query)) {
    echo "Table 'data' created successfully.\n";
} else {
    echo "Error creating table: " . $connection->error . "\n";
}