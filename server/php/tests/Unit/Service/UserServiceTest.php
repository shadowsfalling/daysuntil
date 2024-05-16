<?php

namespace Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use DaysUntil\Service\UserService;
use DaysUntil\Repository\UserRepository;
use Exception;
use PDO;

class UserServiceTest extends TestCase {
    private $userService;
    private $userRepositoryMock;
    private $pdoMock;

    protected function setUp(): void {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->pdoMock->method('beginTransaction')->willReturn(true);
        $this->pdoMock->method('commit')->willReturn(true);
        $this->pdoMock->method('rollBack')->willReturn(true);

        $this->userService = new UserService($this->userRepositoryMock);
    }

    public function testRegisterNewUserSuccessfully() {
        $username = 'newuser';
        $email = 'newuser@example.com';
        $password = 'password123';

        $this->userRepositoryMock->method('findByUsername')
                                 ->willReturn(false);
        $this->userRepositoryMock->expects($this->once())
                                 ->method('createUser')
                                 ->willReturn(true);

        $result = $this->userService->register($username, $email, $password);
        $this->assertTrue($result);
    }

    public function testRegisterWithExistingUsername() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Username already exists");

        $username = 'existinguser';
        $email = 'user@example.com';
        $password = 'password123';

        $this->userRepositoryMock->method('findByUsername')
                                 ->willReturn(['username' => $username]);

        $this->userService->register($username, $email, $password);
    }

    public function testLoginSuccessful() {
        $username = 'testuser';
        $password = 'password123';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $this->userRepositoryMock->method('findByUsername')
                                 ->willReturn(['username' => $username, 'password' => $hashedPassword]);

        $result = $this->userService->login($username, $password);
        $this->assertEquals(['username' => $username, 'password' => $hashedPassword], $result);
    }

    public function testLoginInvalidCredentials() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid login credentials");

        $username = 'wronguser';
        $password = 'wrongpassword';

        $this->userRepositoryMock->method('findByUsername')
                                 ->willReturn(['username' => 'testuser', 'password' => password_hash('password123', PASSWORD_DEFAULT)]);

        $this->userService->login($username, $password);
    }

    public function testSaveToken() {
        $userId = 1;
        $token = 'some-token';

        $this->userRepositoryMock->expects($this->once())
                                 ->method('saveToken')
                                 ->with($userId, $token)
                                 ->willReturn(true);

        $result = $this->userService->saveToken($userId, $token);
        $this->assertTrue($result);
    }

    protected function tearDown(): void {
        $this->userService = null;
        $this->userRepositoryMock = null;
        $this->pdoMock = null;
    }
}

?>
