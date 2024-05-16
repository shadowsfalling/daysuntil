<?php

namespace DaysUntil\Controller;

use DaysUntil\Service\UserService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use OpenApi\Attributes as OA;

class AuthController
{
    private $userService;
    private $secretKey = 'my_super_secret_key';

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[OA\Post(
        path: '/api/auth/register',
        summary: 'Register a new user',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            description: 'Data needed for registering a new user',
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['username', 'password', 'email'],
                    properties: [
                        new OA\Property(property: 'username', type: 'string', description: 'User`s username'),
                        new OA\Property(property: 'email', type: 'string', description: 'User`s email address'),
                        new OA\Property(property: 'password', type: 'string', description: 'User`s password')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'User successfully registered'),
            new OA\Response(response: 400, description: 'Bad request')
        ]
    )]
    public function register($data)
    {
        try {
            if (isset($data['username'], $data['password'], $data['email'])) {
                $userId = $this->userService->register($data['username'], $data['email'], $data['password']);
                if ($userId) {
                    $token = $this->generateToken($userId);
                    $this->userService->saveToken($userId, $token);
                    http_response_code(201);
                    echo json_encode(['message' => 'User successfully registered', 'userId' => $userId, 'token' => $token]);
                } else {
                    throw new Exception("Registration failed");
                }
            } else {
                throw new Exception("Missing required fields");
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    #[OA\Post(
        path: '/api/auth/login',
        summary: 'User login',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            description: 'Data needed for user login',
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['username', 'password'],
                    properties: [
                        new OA\Property(property: 'username', type: 'string', description: 'User`s username'),
                        new OA\Property(property: 'password', type: 'string', description: 'User`s password')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Login successful'),
            new OA\Response(response: 401, description: 'Login failed'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 500, description: 'Internal server error')
        ]
    )]
    public function login($data)
    {
        try {
            if (!isset($data['username'], $data['password'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Missing required fields']);
                return;
            }

            $user = $this->userService->login($data['username'], $data['password']);
            if (!$user) {
                http_response_code(401);
                echo json_encode(['message' => 'Login failed']);
                return;
            }

            $token = $this->generateToken($user['id']);
            if (!$token) {
                http_response_code(500);
                echo json_encode(['message' => 'Failed to generate token']);
                return;
            }

            $this->userService->saveToken($user['id'], $token);
            http_response_code(200);
            echo json_encode(['token' => $token, 'message' => 'Login successful']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function resetPassword()
    {
        // todo: not implemented
    }

    public function sendPasswordReset()
    {
        // todo: not implemented
    }

    public function validateToken($headers)
    {
        if (!isset($headers['Authorization']) && !isset($headers['authorization'])) {
            throw new Exception("No token provided", 401);
        }

        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        $parts = explode(' ', $authHeader);
        if (count($parts) < 2) {
            throw new Exception("Malformed token", 400);
        }

        $token = $parts[1];

        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            throw new Exception("Invalid token: " . $e->getMessage(), 403);
        }
    }

    private function generateToken($userId)
    {
        $payload = [
            'iss' => 'yourdomain.com', // Issuer
            'aud' => 'yourdomain.com', // Audience
            'iat' => time(), // Issued at
            'exp' => time() + 7200, // Expire time
            'sub' => $userId // Subject
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }
}
