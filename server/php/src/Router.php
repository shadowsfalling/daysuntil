<?php

namespace DaysUntil;

use DaysUntil\Controller\AuthController;
use DaysUntil\Controller\CategoryController;
use DaysUntil\Controller\CountdownController;

class Router
{
    private $authController;
    private $countdownController;
    private $categoryController;

    public function __construct(
        AuthController $authController,
        CountdownController $countdownController,
        CategoryController $categoryController
    ) {
        $this->authController = $authController;
        $this->countdownController = $countdownController;
        $this->categoryController = $categoryController;
    }

    public function route()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $uri = str_replace("/daysunitl/server/php", "", $uri);

        if ($method === 'POST') {

            $data = json_decode(file_get_contents("php://input"), true);

            switch ($uri) {
                case '/register':
                    echo $this->authController->register($data);
                    break;
                case '/login':
                    echo $this->authController->login($data);
                    break;
                case '/countdown':
                    echo $this->countdownController->createCountdown($data);
                    break;
                default:
                    http_response_code(404);
                    echo json_encode(['message' => 'Not found']);
                    break;
            }
        } else if ($method === 'PUT') {

            $uriParts = explode('/', trim($uri, '/'));
            $data = json_decode(file_get_contents("php://input"), true);

            if ($uriParts[0] === 'countdown' && isset($uriParts[1])) {
                $countdownId = $uriParts[1];

                if (is_numeric($countdownId)) {
                    echo $this->countdownController->updateCountdown($countdownId, $data);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'Invalid countdown ID']);
                }
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Not found']);
            }
        } else if ($method === 'GET') {
            $uriParts = explode('/', trim($uri, '/'));


            switch ($uri) {
                case '/countdowns/upcoming':
                    echo $this->countdownController->showAllUpcoming();
                    return;
                    break;
                case '/countdowns/past':
                    echo $this->countdownController->showAllExpired();
                    return;
                    break;
                case '/categories':
                    echo $this->categoryController->showAllCategories();
                    return;
                    break;
            }

            if ($uriParts[0] === 'countdown' && isset($uriParts[1])) {
                $countdownId = $uriParts[1];

                if (is_numeric($countdownId)) {
                    echo $this->countdownController->getCountdown($countdownId);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'Invalid countdown ID']);
                }
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Not found']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
        }
    }
}
