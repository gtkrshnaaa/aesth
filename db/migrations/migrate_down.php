<?php
// /db/migrations/migrate_down.php
require __DIR__ . '/../connection.php';

$connection = DatabaseConnection::connect();

// SQL query to drop the "data" table
$query = "DROP TABLE IF EXISTS data";

if ($connection->query($query)) {
    echo "Table 'data' dropped successfully.\n";
} else {
    echo "Error dropping table: " . $connection->error . "\n";
}