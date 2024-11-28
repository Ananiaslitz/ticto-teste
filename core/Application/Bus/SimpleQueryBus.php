<?php

namespace Core\Application\Bus;

use Core\Application\Contracts\Query;
use Core\Application\Contracts\QueryBus;
use Illuminate\Container\Container;

class SimpleQueryBus implements QueryBus
{
    public function __construct(private Container $container) {}

    public function handle(Query $query): mixed
    {
        $queryMappings = config('queries');
        $queryClass = get_class($query);

        if (!isset($queryMappings[$queryClass])) {
            throw new \RuntimeException("Nenhum handler mapeado para a consulta: {$queryClass}");
        }

        $handlerClass = $queryMappings[$queryClass];
        $handler = $this->container->make($handlerClass);

        if (!method_exists($handler, 'handle')) {
            throw new \RuntimeException("Handler não implementa o método 'handle'");
        }

        return $handler->handle($query);
    }
}
