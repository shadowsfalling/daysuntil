<?php

use PHPUnit\Framework\TestCase;
use DaysUntil\Service\CategoryService;
use DaysUntil\Repository\CategoryRepository;

class CategoryServiceTest extends TestCase
{
    private $categoryService;
    private $categoryRepository;

    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);

        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('bindParam')->willReturn(true);
        $this->stmt->method('fetch')->willReturn(['id' => 1, 'name' => 'Test', 'color' => '#000000', 'userId' => 1]);
        $this->stmt->method('fetchColumn')->willReturn(1);

        $this->categoryRepository = $this->createMock(CategoryRepository::class); 
        $this->categoryService = new CategoryService($this->categoryRepository);
    }


    public function testCreateCategory()
    {
        echo "result: 1";

        $name = 'Test Category';
        $color = '#FFFFFF';
        $userId = 1;


        $this->categoryRepository->expects($this->once())
            ->method('addCategory')
            ->with($name, $color, $userId)
            ->willReturn(1);

        $result = $this->categoryService->createCategory($name, $color, $userId);

        echo "result: ".$result;

        $this->assertEquals(1, $result);
    }

    public function testCreateCategoryInvalidColor()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid color format. Provide a valid hex code.");

        $this->categoryService->createCategory("Valid Name", "123456", 1);
    }

    public function testUpdateCategoryUnauthorized()
    {
        $id = 1;
        $name = 'Valid Name';
        $color = '#FFFFFF';
        $userId = 1;

        $this->categoryRepository->method('isUserOwner')
            ->with($id, $userId)
            ->willReturn(false); 

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Unauthorized: You are not the owner of this countdown.");

        $this->categoryService->updateCategory($id, $name, $color, $userId);
    }

    public function testDeleteCategory()
    {
        $categoryId = 1;

        $this->categoryRepository->expects($this->once())
            ->method('deleteCategory')
            ->with($categoryId)
            ->willReturn(1);

        $result = $this->categoryService->deleteCategory($categoryId);
        $this->assertEquals(1, $result);
    }

    public function testGetAllCategories()
    {
        $categories = [
            ['id' => 1, 'name' => 'Category 1', 'color' => '#FFFFFF'],
            ['id' => 2, 'name' => 'Category 2', 'color' => '#000000']
        ];

        $this->categoryRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($categories);

        $result = $this->categoryService->getAllCategories();
        $this->assertEquals($categories, $result);
    }
}
