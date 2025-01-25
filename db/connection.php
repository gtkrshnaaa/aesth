<?php
// db/connection.php

class Database {
    private $host = 'localhost'; 
    private $db_name = 'aesth'; 
    private $username = 'root'; 
    private $password = ''; 
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
