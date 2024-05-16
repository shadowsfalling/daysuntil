<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class CategoryController extends Controller {
    private $categoryService;

    public function __construct(CategoryServiceInterface $categoryService) {
        $this->categoryService = $categoryService;
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
    public function get($id) {

        // todo: validation ob user erlaubt ist zu sehen

        return Category::find($id);
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
    public function create(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string',
        ]);

        try {
            $categoryId = $this->categoryService->createCategory($validated['name'], $validated['color'], $request->user()->id);
            return response()->json(['message' => 'Category created successfully', 'categoryId' => $categoryId], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
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
    public function update(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string'
        ]);

        try {
            $userId = $request->user()->id; 
            $updatedCount = $this->categoryService->updateCategory($id, $validated['name'], $validated['color'], $userId);
            if ($updatedCount) {
                return response()->json(['message' => 'Category updated successfully'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'No changes made or category not found'], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
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
    public function delete($id, Request $request) {
        try {
            $userId = $request->user()->id;
            $deletedCount = $this->categoryService->deleteCategory($id, $userId);
            if ($deletedCount) {
                return response()->json(['message' => 'Category deleted successfully'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Category not found or already deleted'], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function index(Request $request) {
        $userId = $request->user()->id;
        $categories = $this->categoryService->getAllCategories($userId);
        return response()->json($categories, Response::HTTP_OK);
    }
}