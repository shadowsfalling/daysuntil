<?php

use PHPUnit\Framework\TestCase;
use DaysUntil\Repository\CountdownRepository;

class CountdownRepositoryTest extends TestCase
{
    private $pdo;
    private $repository;

    protected function setUp(): void
    {
        $this->pdo = require __DIR__ . '/../db.php';

        $this->repository = new CountdownRepository($this->pdo);
    }

    public function testAddCountdown()
    {
        $title = 'Test Countdown';
        $datetime = '2024-05-10 12:00:00';
        $is_public = true;
        $category_id = 1;
        $user_id = 1;

        $countdownId = $this->repository->addCountdown($title, $datetime, $is_public, $category_id, $user_id);
        $this->assertIsNumeric($countdownId);

        $stmt = $this->pdo->prepare("SELECT * FROM countdowns WHERE id = ?");
        $stmt->execute([$countdownId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals($title, $result['title']);
        $this->assertEquals($datetime, $result['datetime']);
        $this->assertEquals($is_public, $result['is_public']);
        $this->assertEquals($category_id, $result['category_id']);
        $this->assertEquals($user_id, $result['user_id']);
    }

    public function testUpdateCountdown()
    {
        $this->repository->addCountdown('Existing Countdown', '2024-05-10 12:00:00', true, 1, 1);
        $updatedTitle = 'Updated Countdown';
        $updatedDatetime = '2024-05-11 12:00:00';
        $updatedIsPublic = false;
        $updatedCategoryId = 2;
        $this->repository->updateCountdown(1, $updatedTitle, $updatedDatetime, $updatedIsPublic, $updatedCategoryId);

        $updatedCountdown = $this->repository->findById(1);
        $this->assertEquals($updatedTitle, $updatedCountdown->title);
        $this->assertEquals($updatedDatetime, $updatedCountdown->datetime);
        $this->assertEquals($updatedIsPublic, $updatedCountdown->isPublic);
        $this->assertEquals($updatedCategoryId, $updatedCountdown->categoryId);
    }

    public function testIsUserOwner()
    {
        $countdownId = $this->repository->addCountdown('Test Countdown', '2024-05-10 12:00:00', true, 1, 1);

        $this->assertTrue($this->repository->isUserOwner($countdownId, 1));

        $this->assertFalse($this->repository->isUserOwner($countdownId, 2));
    }

    public function testFindAllUpcoming()
    {
        $user_id = 1;
        $currentDate = new DateTime();
        $futureDate1 = $currentDate->modify('+1 day')->format('Y-m-d H:i:s');
        $futureDate2 = $currentDate->modify('+1 day')->format('Y-m-d H:i:s');
    
        $this->repository->addCountdown('Upcoming Countdown 1', $futureDate1, true, 1, $user_id);
        $this->repository->addCountdown('Upcoming Countdown 2', $futureDate2, true, 1, $user_id);
    
        $upcomingCountdowns = $this->repository->findAllUpcoming($user_id);
    
        $this->assertCount(2, $upcomingCountdowns);
    
        $this->assertEquals('Upcoming Countdown 1', $upcomingCountdowns[0]['title']);
        $this->assertEquals('Upcoming Countdown 2', $upcomingCountdowns[1]['title']);
    }

    public function testFindAllExpired()
    {
        $user_id = 1;

        $this->repository->addCountdown('Expired Countdown 1', '2024-05-01 12:00:00', true, 1, $user_id);
        $this->repository->addCountdown('Expired Countdown 2', '2024-05-02 12:00:00', true, 1, $user_id);

        $expiredCountdowns = $this->repository->findAllExpired($user_id);

        $this->assertCount(2, $expiredCountdowns);

        $this->assertEquals('Expired Countdown 2', $expiredCountdowns[0]['title']);
        $this->assertEquals('Expired Countdown 1', $expiredCountdowns[1]['title']);
    }


    protected function tearDown(): void
    {
        $this->pdo->exec("DROP TABLE countdowns");
    }
}
