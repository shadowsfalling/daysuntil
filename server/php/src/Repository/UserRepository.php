<?php

namespace DaysUntil\Repository;

use DaysUntil\db\Database;
use PDO;

class UserRepository {
    private $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function findByUsername($username) {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($username, $password, $email) {
        $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function saveToken($userId, $token) {
        $sql = "UPDATE users SET auth_token = :token WHERE id = :userId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
    
        return $stmt->rowCount() > 0;
    }

}
?>