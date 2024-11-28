<?php

namespace App\Tests\Core\Application\Handlers;

use Core\Application\Commands\UpdatePointCommand;
use Core\Application\Handlers\UpdatePointHandler;
use Core\Domain\Entities\PointEntity;
use Core\Domain\Repositories\PointRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UpdatePointHandlerTest extends TestCase
{
    private $pointRepositoryMock;
    private $updatePointHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pointRepositoryMock = $this->createMock(PointRepositoryInterface::class);
        $this->updatePointHandler = new UpdatePointHandler($this->pointRepositoryMock);
    }

    public function testHandleSuccessfullyUpdatesPoint(): void
    {
        $command = new UpdatePointCommand(
            id: 1,
            userId: 123,
            datetime: new \DateTimeImmutable('2024-11-28 08:00:00'),
            latitude: -23.550520,
            longitude: -46.633308
        );

        $existingPoint = $this->createMock(PointEntity::class);
        $existingPoint->method('getId')->willReturn(1);

        $this->pointRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($existingPoint);

        $this->pointRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (PointEntity $point) use ($command) {
                return $point->getUserId() === $command->userId &&
                    $point->getRegisteredAt() == $command->datetime &&
                    $point->getLatitude() === $command->latitude &&
                    $point->getLongitude() === $command->longitude &&
                    $point->getId() === $command->id;
            }))
            ->willReturnCallback(function (PointEntity $point) {
                return $point;
            });

        $result = $this->updatePointHandler->handle($command);

        $this->assertInstanceOf(PointEntity::class, $result);
        $this->assertEquals(123, $result->getUserId());
    }

    public function testHandleThrowsExceptionWhenPointNotFound(): void
    {
        $command = new UpdatePointCommand(
            id: 1,
            userId: 123,
            datetime: new \DateTimeImmutable('2024-11-28 08:00:00'),
            latitude: -23.550520,
            longitude: -46.633308
        );

        $this->pointRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(null);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Ponto não encontrado.');

        $this->updatePointHandler->handle($command);
    }
}
