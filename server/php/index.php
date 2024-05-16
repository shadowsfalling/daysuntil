<?php

require_once 'vendor/autoload.php';

use DaysUntil\Db\Database;
use DaysUntil\Service\UserService;
use DaysUntil\Controller\AuthController;
use DaysUntil\Controller\CategoryController;
use DaysUntil\Controller\CountdownController;
use DaysUntil\Repository\CategoryRepository;
use DaysUntil\Repository\CountdownRepository;
use DaysUntil\Repository\UserRepository;
use DaysUntil\Router;
use DaysUntil\Service\CountdownService;
use DaysUntil\Service\CategoryService;
use DaysUntil\Middleware;

$middleware = new Middleware();
$middleware->handleCors();

$db = new Database();
$userRepository = new UserRepository($db->getConnection());
$userService = new UserService($userRepository);
$authController = new AuthController($userService);
$countdownRepository = new CountdownRepository($db->getConnection());
$countdownService = new CountdownService($countdownRepository);
$categoryRepository = new CategoryRepository($db->getConnection());
$categoryService = new CategoryService($categoryRepository);
$categoryController = new CategoryController($categoryService, $authController);
$countdownController = new CountdownController($countdownService, $authController);
$router = new Router($authController, $countdownController, $categoryController);

$router->route();

