<?php

namespace App\Tests\Core\Application\Handlers;

use Core\Application\Commands\DeletePointCommand;
use Core\Application\Handlers\DeletePointHandler;
use Core\Domain\Entities\PointEntity;
use Core\Domain\Repositories\PointRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeletePointHandlerTest extends TestCase
{
    private $pointRepositoryMock;
    private $deletePointHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pointRepositoryMock = $this->createMock(PointRepositoryInterface::class);
        $this->deletePointHandler = new DeletePointHandler($this->pointRepositoryMock);
    }

    public function testHandleSuccessfullyDeletesPoint(): void
    {
        $command = new DeletePointCommand(id: 1, userId: 123);

        $point = $this->createMock(PointEntity::class);
        $point->method('getUserId')->willReturn(123);

        $this->pointRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($point);

        $this->pointRepositoryMock
            ->expects($this->once())
            ->method('delete')
            ->with(1);

        $this->deletePointHandler->handle($command);
    }

    public function testHandleThrowsExceptionWhenPointNotFound(): void
    {
        $command = new DeletePointCommand(id: 1, userId: 123);

        $this->pointRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(null);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Ponto não encontrado.');

        $this->deletePointHandler->handle($command);
    }

    public function testHandleThrowsExceptionWhenUserIsNotAuthorized(): void
    {
        $command = new DeletePointCommand(id: 1, userId: 123);

        $point = $this->createMock(PointEntity::class);
        $point->method('getUserId')->willReturn(456);

        $this->pointRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($point);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não autorizado a remover este ponto.');

        $this->deletePointHandler->handle($command);
    }
}
