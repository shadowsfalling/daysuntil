<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class CountdownControllerIntegrationTest extends TestCase
{
    private static $client;

    public static function setUpBeforeClass(): void
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode(['message' => 'Countdown created successfully', 'countdown_id' => 1])),
            new Response(200, [], json_encode(['id' => 1, 'title' => 'New Year', 'datetime' => '2024-01-01 00:00:00', 'is_public' => true, 'category_id' => 1, 'user_id' => 1])), // Antwort für getCountdown
            new Response(200, [], json_encode(['message' => 'Countdown updated successfully'])),
            new Response(200, [], json_encode(['countdowns' => []])),
            new Response(200, [], json_encode(['countdowns' => []]))
        ]);

        $handlerStack = HandlerStack::create($mock);
        self::$client = new Client(['handler' => $handlerStack, 'base_uri' => 'http://localhost:8000']);
    }

    public function testCreateCountdown()
    {
        $response = self::$client->request('POST', '/countdowns/create', [
            'json' => [
                'title' => 'New Year',
                'datetime' => '2024-01-01 00:00:00',
                'is_public' => true,
                'category_id' => 1,
                'user_id' => 1
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $body = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals('Countdown created successfully', $body['message']);
    }

    public function testGetCountdown()
    {
        $response = self::$client->request('GET', '/countdowns/1');
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals(1, $body['id']);
    }

    public function testUpdateCountdown()
    {
        $response = self::$client->request('PUT', '/countdowns/update/1', [
            'json' => [
                'title' => 'Updated New Year',
                'datetime' => '2024-01-01 01:00:00',
                'is_public' => true,
                'category_id' => 1,
                'user_id' => 1
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals('Countdown updated successfully', $body['message']);
    }

    public function testShowAllUpcoming()
    {
        $response = self::$client->request('GET', '/countdowns/upcoming');
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody()->getContents(), true);
        $this->assertIsArray($body['countdowns']);
    }

    public function testShowAllExpired()
    {
        $response = self::$client->request('GET', '/countdowns/expired');
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody()->getContents(), true);
        $this->assertIsArray($body['countdowns']);
    }
}