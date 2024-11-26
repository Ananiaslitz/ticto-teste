<?php

namespace Core\Application\Bus;

use Core\Application\Contracts\Command;
use Core\Application\Contracts\CommandBus;
use Illuminate\Container\Container;

class SimpleCommandBus implements CommandBus
{
    public function __construct(private Container $container) {}

    public function handle(Command $command): mixed
    {
        $commandMappings = config('commands');
        $commandClass = get_class($command);

        if (!isset($commandMappings[$commandClass])) {
            throw new \RuntimeException("Nenhum handler mapeado para o comando: {$commandClass}");
        }

        $handlerClass = $commandMappings[$commandClass];
        $handler = $this->container->make($handlerClass);

        if (!method_exists($handler, 'handle')) {
            throw new \RuntimeException("Handler não implementa o método 'handle'");
        }

        return $handler->handle($command);
    }
}
