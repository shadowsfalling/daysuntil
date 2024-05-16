<?php

namespace DaysUntil\Repository;

use PDO;
use DaysUntil\Model\Category;

class CategoryRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Category($data['id'], $data['name'], $data['color'], $data['user_id']);
    }

    public function addCategory($name, $color, $userId)
    {
        $sql = "INSERT INTO categories (name, color, user_id) VALUES (:name, :color, :userId)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateCategory($id, $name, $color)
    {
        $sql = "UPDATE categories SET name = :name, color = :color WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':color', $color);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteCategory($id)
    {
        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name");
        $stmt->execute();
        $categories = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category($data['id'], $data['name'], $data['color'], $data['user_id']);
        }
        return $categories;
    }

    public function isUserOwner($categoryId, $userId)
    {
        $sql = "SELECT user_id FROM categories WHERE id = :categoryId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        $ownerId = $stmt->fetchColumn();

        return $ownerId == $userId;
    }
}
