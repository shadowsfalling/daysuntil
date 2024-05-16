<?php

namespace App\Http\Controllers;

use App\Services\CountdownServiceInterface;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CountdownController extends Controller
{
    protected $service;

    public function __construct(CountdownServiceInterface $service)
    {
        $this->service = $service;
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
    public function get(Request $request, $id)
    {
        $userId = $request->user()->id; 

        try {
            $countdown = $this->service->getCountdownById($id, $userId);
            return response()->json($countdown);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
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
    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'datetime' => 'required|date',
            'is_public' => 'required|boolean',
            'category_id' => 'required|integer'
        ]);

        try {
            $userId = $request->user()->id;
            $newCountdownId = $this->service->createCountdown(
                $data['name'], $data['datetime'], $data['is_public'], $data['category_id'], $userId
            );
            return response()->json(['message' => 'Countdown created successfully', 'countdown_id' => $newCountdownId], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
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
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'datetime' => 'required|date',
            'is_public' => 'required|boolean',
            'category_id' => 'required|integer'
        ]);

        try {
            $userId = $request->user()->id;
            $this->service->updateCountdown($id, $data['title'], $data['datetime'], $data['is_public'], $data['category_id'], $userId);
            return response()->json(['message' => 'Countdown updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
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
        $countdowns = $this->service->getAllUpcoming();
        return response()->json($countdowns);
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
        $countdowns = $this->service->getAllExpired();
        return response()->json($countdowns);
    }
}