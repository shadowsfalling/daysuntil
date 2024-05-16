<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller {
    private $categoryService;

    public function __construct(CategoryServiceInterface $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function get($id) {

        // todo: validation ob user erlaubt ist zu sehen

        return Category::find($id);
    }

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