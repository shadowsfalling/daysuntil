<?php

namespace App\Repositories;

use App\Repositories\CountdownRepositoryInterface;
use App\Models\Countdown;
use Illuminate\Support\Facades\DB;

class CountdownRepository implements CountdownRepositoryInterface
{
    public function findById($id)
    {
        return Countdown::find($id);
    }

    public function addCountdown($name, $datetime, $is_public, $category_id, $user_id) {
        return Countdown::create([
            'name' => $name,
            'datetime' => $datetime,
            'is_public' => $is_public,
            'category_id' => $category_id,
            'user_id' => $user_id
        ])->id;
    }

    public function updateCountdown($id, $name, $datetime, $is_public, $category_id) {
        $countdown = Countdown::find($id);
        $countdown->update([
            'name' => $name,
            'datetime' => $datetime,
            'is_public' => $is_public,
            'category_id' => $category_id
        ]);
        return $countdown;
    }

    public function isUserOwner($countdownId, $userId) {
        return Countdown::where('id', $countdownId)->first()->user_id == $userId;
    }

    public function findAllUpcoming() {
        return Countdown::where('datetime', '>', now())->orderBy('datetime', 'asc')->get();
    }
    
    public function findAllExpired() {
        return Countdown::where('datetime', '<=', now())->orderBy('datetime', 'desc')->get();
    }
}