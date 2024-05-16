<?php

namespace App\Services;

use App\Services\CountdownServiceInterface;
use App\Repositories\CountdownRepository;
use DateTime;
use Exception;

class CountdownService implements CountdownServiceInterface
{
    private $repository;

    public function __construct(CountdownRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCountdownById($id, $userId)
    {
        $countdown = $this->repository->findById($id);

        if ($countdown->isPublic || $countdown->userId === $userId) {
            return $countdown;
        } else {
            throw new Exception("Unauthorized access.");
        }
    }

    public function createCountdown($name, $datetime, $isPublic, $categoryId, $userId)
    {
        $this->validateCountdown($name, $datetime, $isPublic, $categoryId, $userId);
        return $this->repository->addCountdown($name, $datetime, $isPublic, $categoryId, $userId);
    }

    public function updateCountdown($id, $title, $datetime, $isPublic, $categoryId, $userId)
    {
        if (!$this->repository->isUserOwner($id, $userId)) {
            throw new Exception("Unauthorized: You are not the owner of this countdown.");
        }
        $this->validateCountdown($title, $datetime, $isPublic, $categoryId, $userId);
        return $this->repository->updateCountdown($id, $title, $datetime, $isPublic, $categoryId);
    }

    public function getAllUpcoming()
    {
        return $this->repository->findAllUpcoming();
    }
    
    public function getAllExpired()
    {
        return $this->repository->findAllExpired();
    }

    private function validateDateTime($datetime)
    {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        return $d && $d->format('Y-m-d H:i:s') === $datetime;
    }

    private function validateCountdown($title, $datetime, $isPublic, $categoryId, $userId)
    {
        if (empty($title)) {
            throw new Exception("Der Titel darf nicht leer sein.");
        }
        if (strlen($title) > 255) {
            throw new Exception("Der Titel darf maximal 255 Zeichen lang sein.");
        }
    
        if (!$this->validateDateTime($datetime)) {
            throw new Exception("Das angegebene Datum ist ungültig.");
        }
    
        if (isset($isPublic) && !is_numeric($isPublic) && !is_bool($isPublic)) {
            throw new Exception("Ungültiger Wert für öffentliche Zugänglichkeit.");
        }
    
        if (!is_numeric($categoryId) || $categoryId < 1) {
            throw new Exception("Ungültige Kategorie-ID.");
        }
    
        if (!is_numeric($userId) || $userId < 1) {
            throw new Exception("Ungültige Benutzer-ID.");
        }
    }
}