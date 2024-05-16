<?php

namespace DaysUntil\Db;

use PDO;
use PDOException;

class Database {
    private $host = "localhost";
    private $db_name = "TermineDB";
    private $username = "root";
    private $password = "PASSWORD";
    public $conn;

    
    public function getConnection() {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $exception) {
                echo "Database connection error: " . $exception->getMessage();
            }
        }
        return $this->conn;
    }
}
?>