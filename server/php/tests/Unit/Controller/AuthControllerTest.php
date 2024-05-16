<?php

use PHPUnit\Framework\TestCase;
use DaysUntil\Controller\AuthController;
use DaysUntil\Service\UserService;
use Firebase\JWT\JWT;

class AuthControllerTest extends TestCase
{
    private $controller;
    private $userService;

    protected function setUp(): void
    {
        $this->userService = $this->createMock(UserService::class);
        $this->controller = new AuthController($this->userService);
    }

    public function testRegisterSuccess()
    {
        $data = ['username' => 'testuser', 'password' => 'testpass', 'email' => 'test@test.com'];
        $userId = 1;

        $this->userService->method('register')
            ->with($data['username'], $data['email'], $data['password'])
            ->willReturn($userId);

        $this->userService->expects($this->once())
            ->method('saveToken')
            ->with($this->equalTo($userId), $this->anything());

        ob_start();
        $this->controller->register($data);
        $output = json_decode(ob_get_clean(), true);

        $this->assertEquals(201, http_response_code());
        $this->assertArrayHasKey('token', $output);
        $this->assertEquals('User successfully registered', $output['message']);
        $this->assertEquals($userId, $output['userId']);
    }

    public function testRegisterFailureMissingFields()
    {
        $data = ['username' => 'testuser'];

        ob_start();
        $this->controller->register($data);
        $output = json_decode(ob_get_clean(), true);

        $this->assertEquals(400, http_response_code());
        $this->assertEquals('Missing required fields', $output['message']);
    }
    
    public function testLoginSuccess()
    {
        $data = ['username' => 'testuser', 'password' => 'validPassword'];
        $user = ['id' => 1];
    
        $this->userService->method('login')
            ->willReturn($user);
    
        $this->userService->expects($this->once())
            ->method('saveToken')
            ->with($this->equalTo($user['id']), $this->anything());
    
        ob_start();
        $this->controller->login($data);
        $output = json_decode(ob_get_clean(), true);
    
        $this->assertEquals(200, http_response_code());
        $this->assertArrayHasKey('token', $output);
        $this->assertEquals('Login successful', $output['message']);
    }

    public function testLoginFailure()
    {
        $data = ['username' => 'testuser', 'password' => 'invalidPassword'];

        $this->userService->method('login')
            ->with($data['username'], $data['password'])
            ->willReturn(null);

        ob_start();
        $this->controller->login($data);
        $output = json_decode(ob_get_clean(), true);

        $this->assertEquals(401, http_response_code());
        $this->assertEquals('Login failed', $output['message']);
    }

    public function testValidateTokenSuccess()
    {
        $userId = 1;
        $token = JWT::encode(['sub' => $userId], 'my_super_secret_key', 'HS256');
        $headers = ['Authorization' => "Bearer $token"];

        $result = $this->controller->validateToken($headers);

        $this->assertEquals($userId, $result['sub']);
    }

    public function testValidateTokenFailure()
    {
        $headers = ['Authorization' => "Bearer invalid_token"];
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid token:");

        $this->controller->validateToken($headers);
    }
}
