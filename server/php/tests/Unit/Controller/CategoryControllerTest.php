<?php

use PHPUnit\Framework\TestCase;
use DaysUntil\Controller\CategoryController;
use DaysUntil\Service\CategoryService;
use DaysUntil\Controller\AuthController;

class CategoryControllerTest extends TestCase
{
    private $controller;
    private $categoryService;
    private $authController;

    protected function setUp(): void
    {
        $this->categoryService = $this->createMock(CategoryService::class);
        $this->authController = $this->createMock(AuthController::class);
        $this->controller = new CategoryController($this->categoryService, $this->authController);

        if (!function_exists('getallheaders')) {
            function getallheaders() {
                return [];
            }
        }
    }

    public function testCreateCategory()
    {
        $data = ['name' => 'New Category', 'color' => '#ffffff', 'user_id' => 1];
        $categoryId = 5;

        $this->categoryService->expects($this->once())
            ->method('createCategory')
            ->with($data['name'], $data['color'], $data['user_id'])
            ->willReturn($categoryId);

        ob_start();
        $this->controller->createCategory($data);
        $output = json_decode(ob_get_clean(), true);

        $this->assertEquals('Category created successfully', $output['message']);
        $this->assertEquals($categoryId, $output['categoryId']);
    }

    public function testUpdateCategory()
    {
        $id = 3;
        $data = ['name' => 'Updated Category', 'color' => '#000000', 'user_id' => 1];
        $tokenData = ['sub' => 1];

        $this->authController->method('validateToken')
            ->willReturn($tokenData);

        $this->categoryService->expects($this->once())
            ->method('updateCategory')
            ->with($id, $data['name'], $data['color'], $tokenData['sub'])
            ->willReturn(1);

        ob_start();
        $this->controller->updateCategory($id, $data);
        $output = json_decode(ob_get_clean(), true);

        $this->assertEquals('Category updated successfully', $output['message']);
    }

    public function testDeleteCategory()
    {
        $id = 4;

        $this->categoryService->expects($this->once())
            ->method('deleteCategory')
            ->with($id)
            ->willReturn(1);

        ob_start();
        $this->controller->deleteCategory($id);
        $output = json_decode(ob_get_clean(), true);

        $this->assertEquals('Category deleted successfully', $output['message']);
    }

    public function testShowAllCategories()
    {
        $categories = [
            ['id' => 1, 'name' => 'Category 1', 'color' => '#ff0000'],
            ['id' => 2, 'name' => 'Category 2', 'color' => '#00ff00']
        ];

        $this->categoryService->expects($this->once())
            ->method('getAllCategories')
            ->willReturn($categories);

        ob_start();
        $this->controller->showAllCategories();
        $output = json_decode(ob_get_clean(), true);

        $this->assertEquals(2, count($output));
        $this->assertEquals($categories, $output);

    }
}
