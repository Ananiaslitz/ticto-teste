<?php

namespace Core\Application\Commands;

use Core\Application\Contracts\Command;
use DateTimeImmutable;

class UpdatePointCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public readonly DateTimeImmutable $datetime,
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null
    ) {}
}
