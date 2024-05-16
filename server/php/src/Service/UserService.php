<?php

namespace DaysUntil\Service;

use DaysUntil\Repository\UserRepository;
use Exception;

class UserService {
    private $userRepository;

    public function __construct(UserRepository $repository) {
        $this->userRepository = $repository;
    }

    public function register($username, $email, $password) {
        if ($this->userRepository->findByUsername($username) === false) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            return $this->userRepository->createUser($username, $hashedPassword, $email);
        } else {
            throw new Exception("Username already exists");
        }
    }

    public function login($username, $password) {
        $user = $this->userRepository->findByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            throw new Exception("Invalid login credentials");
        }
    }

    public function saveToken($userId, $token) {
        return $this->userRepository->saveToken($userId, $token);
    }
}
?>