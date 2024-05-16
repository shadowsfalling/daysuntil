<?php

use PHPUnit\Framework\TestCase;
use DaysUntil\Repository\CategoryRepository;
use DaysUntil\Model\Category;

class CategoryRepositoryTest extends TestCase
{
    private $pdo;
    private $repository;

    protected function setUp(): void
    {
        $this->pdo = require __DIR__ . '/../db.php';

        $this->repository = new CategoryRepository($this->pdo);
    }

    public function testAddCategory()
    {
        $name = 'Test Category';
        $color = '#FFFFFF';
        $userId = 1;

        $categoryId = $this->repository->addCategory($name, $color, $userId);
        $this->assertIsNumeric($categoryId);

        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$categoryId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals($name, $result['name']);
        $this->assertEquals($color, $result['color']);
        $this->assertEquals($userId, $result['user_id']);
    }

    public function testUpdateCategory()
    {
        $this->repository->addCategory('Existing Category', '#000000', 1);

        $category = new Category(1, 'Updated Category', '#FFFFFF', 1);
        $this->repository->updateCategory($category->id, $category->name, $category->color);

        $updatedCategory = $this->repository->findById($category->id);

        $this->assertEquals($category, $updatedCategory);
    }

    public function testDeleteCategory()
    {
        $this->repository->addCategory('Category to delete', '#000000', 1);
        $deletedCount = $this->repository->deleteCategory(1);

        $this->assertEquals(1, $deletedCount);
    }

    public function testFindAll()
    {
        $this->repository->addCategory('Category 1', '#000000', 1);
        $this->repository->addCategory('Category 2', '#FFFFFF', 1);
        $this->repository->addCategory('Category 3', '#123456', 2);

        $categories = $this->repository->findAll();

        $this->assertCount(3, $categories);

        $this->assertInstanceOf(Category::class, $categories[0]);

        $this->assertEquals('Category 1', $categories[0]->name);
        $this->assertEquals('#000000', $categories[0]->color);
        $this->assertEquals(1, $categories[0]->userId);
    }

    protected function tearDown(): void
    {
        $this->pdo->exec("DROP TABLE categories");
    }
}
