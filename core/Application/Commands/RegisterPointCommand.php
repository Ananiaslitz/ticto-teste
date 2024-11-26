<?php

namespace Core\Application\Commands;

use Core\Application\Contracts\Command;

class RegisterPointCommand implements Command
{
    public function __construct(
        public readonly int $userId,
        public readonly \DateTimeImmutable $datetime,
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null
    ) {}
}
