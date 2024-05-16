<?php

namespace Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use DaysUntil\Service\CountdownService;
use DaysUntil\Repository\CountdownRepository;
use Exception;
use PDO;

class CountdownServiceTest extends TestCase
{
    private $service;
    private $countdownRepositoryMock;

    protected function setUp(): void
    {
        $this->countdownRepositoryMock = $this->createMock(CountdownRepository::class);
        $this->service = new CountdownService($this->countdownRepositoryMock);
    }

    public function testGetCountdownById_WithValidAccess()
    {
        $id = 1;
        $userId = 1;
        $countdownData = (object)['id' => $id, 'isPublic' => true, 'userId' => $userId];
        $this->countdownRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($countdownData);

        $countdown = $this->service->getCountdownById($id, $userId);
        $this->assertSame($countdownData, $countdown);
    }

    public function testGetCountdownById_WithUnauthorizedAccess()
    {
        $invalidUserId = 2;
        $countdownId = 1;

        $this->expectException(Exception::class);

        $this->service->getCountdownById($countdownId, $invalidUserId);
    }

    public function testCreateCountdown_WithValidData()
    {
        $dbMock = $this->createMock(PDO::class);
    
        $repositoryMock = $this->getMockBuilder(CountdownRepository::class)
                               ->setConstructorArgs([$dbMock])
                               ->onlyMethods(['addCountdown'])
                               ->getMock();
    
        $repositoryMock->expects($this->once())
                       ->method('addCountdown')
                       ->with(
                           $this->equalTo('Title'),
                           $this->equalTo('2024-05-08 12:00:00'),
                           $this->equalTo(true),  
                           $this->equalTo(1),
                           $this->equalTo(1)
                       )
                       ->willReturn(1);
    
        $service = new CountdownService($repositoryMock);
    
        $countdownId = $service->createCountdown('Title', '2024-05-08 12:00:00', true, 1, 1);
    
        $this->assertEquals(1, $countdownId);
    }

    protected function tearDown(): void
    {
        $this->service = null;
        $this->countdownRepositoryMock = null;
    }
}
