<?php

namespace App\Http\Controllers;

use App\Services\CountdownServiceInterface;
use Illuminate\Http\Request;

class CountdownController extends Controller
{
    protected $service;

    public function __construct(CountdownServiceInterface $service)
    {
        $this->service = $service;
    }

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

    public function showAllUpcoming()
    {
        $countdowns = $this->service->getAllUpcoming();
        return response()->json($countdowns);
    }

    public function showAllExpired()
    {
        $countdowns = $this->service->getAllExpired();
        return response()->json($countdowns);
    }
}