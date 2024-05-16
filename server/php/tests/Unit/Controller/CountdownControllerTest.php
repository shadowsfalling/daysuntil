<?php

use PHPUnit\Framework\TestCase;
use DaysUntil\Controller\CountdownController;
use DaysUntil\Service\CountdownService;
use DaysUntil\Controller\AuthController;

class CountdownControllerTest extends TestCase
{
    private $controller;
    private $countdownService;
    private $authController;

    protected function setUp(): void
    {
        $this->countdownService = $this->createMock(CountdownService::class);
        $this->authController = $this->createMock(AuthController::class);
        $this->controller = new CountdownController($this->countdownService, $this->authController);
    }

    public function testCreateCountdownSuccess()
    {
        $data = ['title' => 'New Event', 'datetime' => '2023-12-25 00:00:00', 'is_public' => true, 'category_id' => 3];
        $newCountdownId = 5;

        $this->authController->method('validateToken')
            ->willReturn(['sub' => 1]);

        $this->countdownService->expects($this->once())
            ->method('createCountdown')
            ->willReturn($newCountdownId);

        ob_start();
        $this->controller->createCountdown($data);
        $output = ob_get_clean();
        $expectedOutput = json_encode(['message' => 'Countdown created successfully', 'countdown_id' => $newCountdownId]);
        $this->assertEquals($expectedOutput, $output);
    }

    public function testUpdateCountdownSuccess()
    {
        $id = 1;
        $data = ['title' => 'Updated Event', 'datetime' => '2024-01-01 00:00:00', 'is_public' => true, 'category_id' => 3];

        $this->authController->method('validateToken')
            ->willReturn(['sub' => 1]);

        $this->countdownService->expects($this->once())
            ->method('updateCountdown');

        ob_start();
        $this->controller->updateCountdown($id, $data);
        $output = ob_get_clean();
        $this->assertEquals('{"message":"Countdown updated successfully"}', $output);
    }

    public function testShowAllUpcoming()
    {
        $countdowns = [
            ['id' => 1, 'title' => 'Upcoming Event', 'datetime' => '2023-12-31 00:00:00', 'is_public' => true]
        ];

        $this->countdownService->method('getAllUpcoming')
            ->willReturn($countdowns);

        ob_start();
        $this->controller->showAllUpcoming();
        $output = ob_get_clean();
        $this->assertEquals(json_encode($countdowns), $output);
    }

    public function testShowAllExpired()
    {
        $countdowns = [
            ['id' => 2, 'title' => 'Past Event', 'datetime' => '2020-01-01 00:00:00', 'is_public' => false]
        ];

        $this->countdownService->method('getAllExpired')
            ->willReturn($countdowns);

        ob_start();
        $this->controller->showAllExpired();
        $output = ob_get_clean();
        $this->assertEquals(json_encode($countdowns), $output);
    }
}
