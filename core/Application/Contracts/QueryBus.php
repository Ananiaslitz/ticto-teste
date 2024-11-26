<?php

namespace Core\Application\Contracts;

interface QueryBus
{
    public function handle(Query $query): mixed;
}
