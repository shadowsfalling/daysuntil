<?php

namespace App\Services;

interface CategoryServiceInterface {
    public function createCategory($name, $color, $userId);
    public function updateCategory($id, $name, $color, $userId);
    public function deleteCategory($id, $userId);
    public function getAllCategories($userId);
}