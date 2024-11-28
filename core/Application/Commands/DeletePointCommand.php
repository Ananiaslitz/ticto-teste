<?php

namespace Core\Application\Commands;

use Core\Application\Contracts\Command;

class DeletePointCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId
    ) {}
}
