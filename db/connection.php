<?php
// db/connection.php

class Database {
    private $host = 'localhost'; // Ganti dengan host database kamu
    private $db_name = 'aesth'; // Ganti dengan nama database kamu
    private $username = 'root'; // Ganti dengan username database kamu
    private $password = ''; // Ganti dengan password database kamu
    private $conn;

    // Koneksi ke database
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
