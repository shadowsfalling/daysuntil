<?php 
use PHPUnit\Framework\TestCase;
use DaysUntil\Repository\UserRepository;

class UserRepositoryTest extends TestCase
{
    private $pdo;
    private $repository;

    protected function setUp(): void
    {
        $this->pdo = require __DIR__ . '/../db.php'; 

        $this->repository = new UserRepository($this->pdo);
    }

    public function testFindByUsername()
    {
        $this->repository->createUser('test_user', 'test_password', 'test@example.com');

        $user = $this->repository->findByUsername('test_user');

        $this->assertNotNull($user);
        $this->assertEquals('test_user', $user['username']);
    }

    public function testCreateUser()
    {
        $userId = $this->repository->createUser('new_user', 'new_password', 'new@example.com');

        $this->assertIsNumeric($userId);

        $user = $this->repository->findByUsername('new_user');

        $this->assertNotNull($user);
        $this->assertEquals('new_user', $user['username']);
    }

    public function testSaveToken()
    {
        $userId = $this->repository->createUser('token_user', 'token_password', 'token@example.com');

        $token = 'test_token';
        $result = $this->repository->saveToken($userId, $token);

        $this->assertTrue($result);

        $user = $this->repository->findByUsername('token_user');

        $this->assertEquals($token, $user['auth_token']);
    }

    protected function tearDown(): void
    {
        $this->pdo->exec("DELETE FROM users");
    }
}