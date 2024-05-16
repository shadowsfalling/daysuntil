<?php

namespace App\Services;

interface CountdownServiceInterface
{
    public function getCountdownById($id, $userId);
    public function createCountdown($title, $datetime, $isPublic, $categoryId, $userId);
    public function updateCountdown($id, $title, $datetime, $isPublic, $categoryId, $userId);
    public function getAllUpcoming();
    public function getAllExpired();
}