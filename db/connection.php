<?php
// /db/connection.php
class DatabaseConnection {
    private static $connection;

    public static function connect() {
        try {
            if (self::$connection === null) {
                self::$connection = new mysqli('localhost', 'root', '', 'aesth');
                if (self::$connection->connect_error) {
                    throw new Exception('Connection failed: ' . self::$connection->connect_error);
                }
                echo "Successfully connected to the database.";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return self::$connection;
    }
}

DatabaseConnection::connect();
