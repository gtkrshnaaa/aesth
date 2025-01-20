<?php
// /db/connection.php
class DatabaseConnection {
    private static $connection;

    public static function connect() {
        if (self::$connection === null) {
            self::$connection = new mysqli('localhost', 'root', '', 'test'); // Update DB credentials
            if (self::$connection->connect_error) {
                die('Connection failed: ' . self::$connection->connect_error);
            }
        }
        return self::$connection;
    }
}