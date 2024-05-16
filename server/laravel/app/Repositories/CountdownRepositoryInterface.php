<?php

namespace App\Repositories;

use App\Models\Countdown;

interface CountdownRepositoryInterface
{
    public function findById($id);
    public function addCountdown($title, $datetime, $is_public, $category_id, $user_id);
    public function updateCountdown($id, $title, $datetime, $is_public, $category_id);
    public function isUserOwner($countdownId, $userId);
    public function findAllUpcoming();
    public function findAllExpired();
}