<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository {
    public function addCategory($name, $color, $userId) {
        return Category::create([
            'name' => $name,
            'color' => $color,
            'user_id' => $userId
        ]);
    }

    public function updateCategory($id, $name, $color) {
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $name,
            'color' => $color
        ]);
        return $category;
    }

    public function deleteCategory($id) {
        $category = Category::findOrFail($id);
        $category->delete();
    }

    public function findAll() {
        return Category::all();
    }

    public function isUserOwner($id, $userId) {
        $category = Category::findOrFail($id);
        return $category->user_id === $userId;
    }
}