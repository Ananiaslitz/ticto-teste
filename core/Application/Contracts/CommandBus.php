<?php

namespace Core\Application\Contracts;

interface CommandBus
{
    public function handle(Command $command): mixed;
}
