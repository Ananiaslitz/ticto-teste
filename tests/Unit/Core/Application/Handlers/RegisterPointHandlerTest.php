<?php

namespace App\Tests\Core\Application\Handlers;

use Core\Application\Commands\RegisterPointCommand;
use Core\Application\Handlers\RegisterPointHandler;
use Core\Domain\Entities\PointEntity;
use Core\Domain\Repositories\PointRepositoryInterface;
use Tests\TestCase;

class RegisterPointHandlerTest extends TestCase
{
    private $pointRepositoryMock;
    private $registerPointHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pointRepositoryMock = $this->createMock(PointRepositoryInterface::class);
        $this->registerPointHandler = new RegisterPointHandler($this->pointRepositoryMock);
    }

    public function testHandleSuccessfullyRegistersPoint(): void
    {
        $command = new RegisterPointCommand(
            userId: 123,
            datetime: new \DateTimeImmutable('2024-11-28 08:00:00'),
            latitude: -23.550520,
            longitude: -46.633308
        );

        $this->pointRepositoryMock
            ->expects($this->once())
            ->method('findLastPointByUser')
            ->with(123)
            ->willReturn(null);

        $this->pointRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (PointEntity $point) use ($command) {
                return $point->getUserId() === $command->userId &&
                    $point->getRegisteredAt() == $command->datetime &&
                    $point->getLatitude() === $command->latitude &&
                    $point->getLongitude() === $command->longitude;
            }))
            ->willReturnCallback(function (PointEntity $point) {
                return $point;
            });

        $result = $this->registerPointHandler->handle($command);

        $this->assertInstanceOf(PointEntity::class, $result);
        $this->assertEquals(123, $result->getUserId());
    }
}
