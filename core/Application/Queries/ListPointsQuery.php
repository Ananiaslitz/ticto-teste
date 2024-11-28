<?php

namespace Core\Application\Queries;

use Core\Application\Contracts\Query;

class ListPointsQuery implements Query
{
    public function __construct(
        public readonly int $userId,
        public readonly ?\DateTimeImmutable $startDate = null,
        public readonly ?\DateTimeImmutable $endDate = null
    ) {}
}
