<?php

namespace DaysUntil\Repository;

use PDO;
use DaysUntil\Model\Countdown;

class CountdownRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM countdowns WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Countdown($data['id'], $data['title'], $data['datetime'], $data['is_public'], $data['category_id'], $data['user_id']);
    }

    public function addCountdown($title, $datetime, $is_public, $category_id, $user_id) {
        $sql = "INSERT INTO countdowns (title, datetime, is_public, category_id, user_id) VALUES (:title, :datetime, :is_public, :category_id, :user_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':datetime', $datetime);
        $stmt->bindParam(':is_public', $is_public);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateCountdown($id, $title, $datetime, $isPublic, $categoryId) {
        $sql = "UPDATE countdowns SET title = :title, datetime = :datetime, is_public = :is_public, category_id = :category_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':datetime', $datetime);
        $stmt->bindParam(':is_public', $isPublic);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function isUserOwner($countdownId, $userId) {
        $sql = "SELECT user_id FROM countdowns WHERE id = :countdownId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':countdownId', $countdownId, PDO::PARAM_INT);
        $stmt->execute();
        $ownerId = $stmt->fetchColumn();
    
        return $ownerId == $userId;
    }

    public function findAllUpcoming($userId) {
        $sql = "SELECT * FROM countdowns WHERE datetime > CURRENT_TIMESTAMP AND user_id = :userId ORDER BY datetime ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findAllExpired($userId) {
        $sql = "SELECT * FROM countdowns WHERE datetime <= CURRENT_TIMESTAMP AND user_id = :userId ORDER BY datetime DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}