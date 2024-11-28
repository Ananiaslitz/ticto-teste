<?php

namespace App\Tests\Core\Application\Bus;

use Core\Application\Bus\SimpleCommandBus;
use Core\Application\Contracts\Command;
use Illuminate\Container\Container;
use Tests\TestCase;

class SimpleCommandBusTest extends TestCase
{
    private $containerMock;
    private $simpleCommandBus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->containerMock = $this->createMock(Container::class);

        $this->simpleCommandBus = new SimpleCommandBus($this->containerMock);
    }

    public function testHandleSuccessfullyInvokesHandler(): void
    {
        $commandMock = $this->createMock(Command::class);
        $commandClass = get_class($commandMock);

        config(['commands' => [$commandClass => 'App\Handlers\SampleCommandHandler']]);

        $handlerMock = $this->createMock(SampleCommandHandler::class);

        $handlerMock
            ->expects($this->once())
            ->method('handle')
            ->with($commandMock)
            ->willReturn('success');

        $this->containerMock
            ->expects($this->once())
            ->method('make')
            ->with('App\Handlers\SampleCommandHandler')
            ->willReturn($handlerMock);

        $result = $this->simpleCommandBus->handle($commandMock);

        $this->assertEquals('success', $result);
    }

    public function testHandleThrowsExceptionWhenNoHandlerIsMapped(): void
    {
        $commandMock = $this->createMock(Command::class);
        $commandClass = get_class($commandMock);

        config(['commands' => []]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Nenhum handler mapeado para o comando: {$commandClass}");

        $this->simpleCommandBus->handle($commandMock);
    }

    public function testHandleThrowsExceptionWhenHandlerDoesNotHaveHandleMethod(): void
    {
        $commandMock = $this->createMock(Command::class);
        $commandClass = get_class($commandMock);

        config(['commands' => [$commandClass => 'App\Handlers\InvalidCommandHandler']]);

        $invalidHandlerMock = new class {};

        $this->containerMock
            ->expects($this->once())
            ->method('make')
            ->with('App\Handlers\InvalidCommandHandler')
            ->willReturn($invalidHandlerMock);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Handler não implementa o método 'handle'");

        $this->simpleCommandBus->handle($commandMock);
    }
}

class SampleCommandHandler
{
    public function handle(Command $command)
    {
        return 'handled';
    }
}
