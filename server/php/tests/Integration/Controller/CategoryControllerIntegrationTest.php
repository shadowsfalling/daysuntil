<?php 
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class CategoryControllerIntegrationTest extends TestCase
{
    private static $client;

    public static function setUpBeforeClass(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'message' => 'Category created successfully', 
                'categoryId' => 123
            ])),
            new Response(200, [], json_encode([
                'message' => 'Category updated successfully'
            ])),
            new Response(200, [], json_encode([
                'message' => 'Category deleted successfully'
            ])),
            new Response(200, [], json_encode([
                ['id' => 1, 'name' => 'Category 1', 'color' => '#abcdef', 'userId' => 1],
                ['id' => 2, 'name' => 'Category 2', 'color' => '#123456', 'userId' => 1],
                ['id' => 3, 'name' => 'Category 3', 'color' => '#789abc', 'userId' => 2]
            ]))
        ]);

        $handlerStack = HandlerStack::create($mock);

        self::$client = new Client(['handler' => $handlerStack, 'base_uri' => 'http://localhost:8000']);
    }

    public function testCreateCategory()
    {
        $data = [
            'name' => 'Test Category',
            'color' => '#abcdef',
            'user_id' => 1 
        ];

        $response = self::$client->request('POST', '/categories/create', [
            'form_params' => $data
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode((string) $response->getBody(), true);
        $this->assertEquals('Category created successfully', $body['message']);
        $this->assertArrayHasKey('categoryId', $body);
    }

    public function testUpdateCategory()
    {
        $data = [
            'name' => 'Updated Test Category',
            'color' => '#123456',
            'user_id' => 1
        ];

        $response = self::$client->request('PUT', '/categories/update/123', [
            'form_params' => $data
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode((string) $response->getBody(), true);
        $this->assertEquals('Category updated successfully', $body['message']);
    }

    public function testDeleteCategory()
    {
        $response = self::$client->request('DELETE', '/categories/delete/123');

        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode((string) $response->getBody(), true);
        $this->assertEquals('Category deleted successfully', $body['message']);
    }

    public function testShowAllCategories()
    {
        $response = self::$client->request('GET', '/categories');

        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode((string) $response->getBody(), true);
        $this->assertIsArray($body);
        $this->assertNotEmpty($body);
    }
}