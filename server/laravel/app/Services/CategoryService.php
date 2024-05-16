<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Services\CategoryServiceInterface;
use Exception;

class CategoryService implements CategoryServiceInterface {
    private $repository;

    public function __construct(CategoryRepository $repository) {
        $this->repository = $repository;
    }

    public function createCategory($name, $color, $userId) {
        if (empty($name)) {
            throw new Exception("The category name cannot be empty.");
        }
        if (strlen($name) > 255) {
            throw new Exception("The category name cannot exceed 255 characters.");
        }
        if (!preg_match('/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/', $color)) {
            throw new Exception("Invalid color format. Provide a valid hex code.");
        }
        return $this->repository->addCategory($name, $color, $userId);
    }

    public function updateCategory($id, $name, $color, $userId) {
        if (!$this->repository->isUserOwner($id, $userId)) {
            throw new Exception("Unauthorized: You are not the owner of this category.");
        }

        if (empty($name)) {
            throw new Exception("The category name cannot be empty.");
        }
        if (strlen($name) > 255) {
            throw new Exception("The category name cannot exceed 255 characters.");
        }
        if (!preg_match('/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/', $color)) {
            throw new Exception("Invalid color format. Provide a valid hex code.");
        }
        return $this->repository->updateCategory($id, $name, $color);
    }

    public function deleteCategory($id, $userId) {

        if (!$this->repository->isUserOwner($id, $userId)) {
            throw new Exception("Unauthorized: You are not the owner of this category.");
        }

        return $this->repository->deleteCategory($id);
    }

    public function getAllCategories($userId) {
        return $this->repository->findAll()->where('user_id', '=', $userId);
    }
}