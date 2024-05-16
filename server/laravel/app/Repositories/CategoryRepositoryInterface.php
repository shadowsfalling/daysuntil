<?php

namespace App\Repositories;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function all();
    public function findById($id);
    public function create(array $data);
    public function update(Category $category, array $data);
    public function delete(Category $category);
}