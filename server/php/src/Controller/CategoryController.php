<?php

namespace DaysUntil\Controller;

use DaysUntil\Service\CategoryService;
use OpenApi\Attributes as OA;

#[OA\Info(title: "Days Until API", version: "1.0")]
class CategoryController {
    private $service;
    private $authController;

    public function __construct(CategoryService $service, AuthController $authController) {
        $this->service = $service;
        $this->authController = $authController;
    }

    #[OA\Post(
        path: '/api/categories',
        summary: 'Create a new category',
        tags: ['Category'],
        requestBody: new OA\RequestBody(
            description: 'Data needed to create a new category',
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['name', 'color', 'user_id'],
                    properties: [
                        new OA\Property(property: 'name', type: 'string', description: 'Name of the category'),
                        new OA\Property(property: 'color', type: 'string', description: 'Color associated with the category'),
                        new OA\Property(property: 'user_id', type: 'integer', description: 'User ID of the category owner')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Category created successfully'),
            new OA\Response(response: 400, description: 'Error creating category')
        ]
    )]
    public function createCategory($data) {
        try {
            $categoryId = $this->service->createCategory($data['name'], $data['color'], $data['user_id']);
            echo json_encode(['message' => 'Category created successfully', 'categoryId' => $categoryId]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    #[OA\Put(
        path: '/api/categories/{id}',
        summary: 'Update an existing category',
        tags: ['Category'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the category to update',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            description: 'Data needed to update the category',
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'name', type: 'string', description: 'Name of the category'),
                        new OA\Property(property: 'color', type: 'string', description: 'Color of the category'),
                        new OA\Property(property: 'user_id', type: 'integer', description: 'User ID of the category owner')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Category updated successfully'),
            new OA\Response(response: 400, description: 'Error updating category')
        ]
    )]
    public function updateCategory($id, $data) {
        try {
            $tokenData = $this->authController->validateToken(getallheaders());
            $userId = $tokenData['sub'];

            $updatedCount = $this->service->updateCategory($id, $data['name'], $data['color'], $userId);
            if ($updatedCount) {
                echo json_encode(['message' => 'Category updated successfully']);
            } else {
                echo json_encode(['message' => 'No changes made or category not found']);
            }
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    #[OA\Delete(
        path: '/api/categories/{id}',
        summary: 'Delete a category',
        tags: ['Category'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the category to delete',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Category deleted successfully'),
            new OA\Response(response: 400, description: 'Error deleting category')
        ]
    )]
    public function deleteCategory($id) {
        try {
            $deletedCount = $this->service->deleteCategory($id);
            if ($deletedCount) {
                echo json_encode(['message' => 'Category deleted successfully']);
            } else {
                echo json_encode(['message' => 'Category not found or already deleted']);
            }
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    #[OA\Get(
        path: '/api/categories',
        summary: 'List all categories',
        tags: ['Category'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of all categories',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Category')
                )
            )
        ]
    )]
    public function showAllCategories() {
        $categories = $this->service->getAllCategories();
        echo json_encode($categories);
    }
}