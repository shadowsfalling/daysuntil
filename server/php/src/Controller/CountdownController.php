<?php

namespace DaysUntil\Controller;

use DaysUntil\Service\CountdownService;
use Exception;
use OpenApi\Attributes as OA;

class CountdownController
{
    private $service;
    private $authController;

    public function __construct(CountdownService $service, AuthController $authController)
    {
        $this->service = $service;
        $this->authController = $authController;
    }

    #[OA\Get(
        path: '/api/countdowns/{id}',
        summary: 'Get a countdown by ID',
        tags: ['Countdown'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the countdown to retrieve',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Countdown retrieved successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Countdown')
            ),
            new OA\Response(response: 403, description: 'Forbidden')
        ]
    )]
    public function getCountdown($id)
    {
        try {
            $tokenData = $this->authController->validateToken(getallheaders());
            $userId = $tokenData['sub'];

            $countdown = $this->service->getCountdownById($id, $userId);
            return json_encode([
                'id' => $countdown->id,
                'title' => $countdown->title,
                'datetime' => $countdown->datetime,
                'is_public' => $countdown->isPublic,
                'category_id' => $countdown->categoryId,
                'user_id' => $countdown->userId
            ]);
        } catch (\Exception $e) {
            http_response_code(403);
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    #[OA\Post(
        path: '/api/countdowns',
        summary: 'Create a new countdown',
        tags: ['Countdown'],
        requestBody: new OA\RequestBody(
            description: 'Data needed to create a new countdown',
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(ref: '#/components/schemas/Countdown')
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Countdown created successfully'),
            new OA\Response(response: 403, description: 'Forbidden')
        ]
    )]
    public function createCountdown($data)
    {
        try {
            $tokenData = $this->authController->validateToken(getallheaders());
            $userId = $tokenData['sub'];

            $newCountdownId = $this->service->createCountdown($data['title'], $data['datetime'], $data['is_public'], $data['category_id'], $userId);
            http_response_code(201);
            echo json_encode(['message' => 'Countdown created successfully', 'countdown_id' => $newCountdownId]);
        } catch (Exception $e) {
            http_response_code(403);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    #[OA\Put(
        path: '/api/countdowns/{id}',
        summary: 'Update an existing countdown',
        tags: ['Countdown'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID of the countdown to update',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            description: 'Data to update the countdown',
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(ref: '#/components/schemas/Countdown')
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Countdown updated successfully'),
            new OA\Response(response: 403, description: 'Forbidden')
        ]
    )]
    public function updateCountdown($id, $data)
    {
        try {
            $tokenData = $this->authController->validateToken(getallheaders());
            $userId = $tokenData['sub'];

            $this->service->updateCountdown($id, $data['title'], $data['datetime'], $data['is_public'], $data['category_id'], $userId);
            http_response_code(200);
            echo json_encode(['message' => 'Countdown updated successfully']);
        } catch (Exception $e) {
            http_response_code(403);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    #[OA\Get(
        path: '/api/countdowns/upcoming',
        summary: 'List all upcoming countdowns',
        tags: ['Countdown'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of upcoming countdowns',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Countdown')
                )
            )
        ]
    )]
    public function showAllUpcoming()
    {

        $tokenData = $this->authController->validateToken(getallheaders());
        $userId = $tokenData['sub'];

        $countdowns = $this->service->getAllUpcoming($userId);
        echo json_encode($countdowns);
    }

    #[OA\Get(
        path: '/api/countdowns/expired',
        summary: 'List all expired countdowns',
        tags: ['Countdown'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of upcoming countdowns',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Countdown')
                )
            )
        ]
    )]
    public function showAllExpired()
    {
        $tokenData = $this->authController->validateToken(getallheaders());
        $userId = $tokenData['sub'];

        $countdowns = $this->service->getAllExpired($userId);
        echo json_encode($countdowns);
    }
}
