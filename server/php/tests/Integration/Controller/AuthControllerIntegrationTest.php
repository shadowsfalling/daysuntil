<?php 
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class AuthControllerIntegrationTest extends TestCase
{
    private static $client;

    public static function setUpBeforeClass(): void
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode([
                'message' => 'User successfully registered', 
                'userId' => 123,
                'token' => 'example_token'
            ])),
            new Response(200, [], json_encode([
                'token' => 'example_token',
                'message' => 'Login successful'
            ]))
        ]);

        $handlerStack = HandlerStack::create($mock);

        self::$client = new Client(['handler' => $handlerStack, 'base_uri' => 'http://localhost:8000']);
    }

    public function testRegister()
    {
        $data = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password'
        ];

        $response = self::$client->request('POST', '/register', [
            'json' => $data
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $body = json_decode((string) $response->getBody(), true);
        $this->assertEquals('User successfully registered', $body['message']);
        $this->assertArrayHasKey('userId', $body);
        $this->assertArrayHasKey('token', $body);
    }

    public function testLogin()
    {
        $data = [
            'username' => 'testuser',
            'password' => 'password'
        ];

        $response = self::$client->request('POST', '/login', [
            'json' => $data
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode((string) $response->getBody(), true);
        $this->assertEquals('Login successful', $body['message']);
        $this->assertArrayHasKey('token', $body);
    }
}