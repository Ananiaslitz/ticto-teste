<?php

namespace Core\Application\Queries\Users;

use Core\Application\Contracts\Query;

class EmployeePointsReportQuery implements Query
{
    public function __construct(
        public readonly ?\DateTimeImmutable $startDate = null,
        public readonly ?\DateTimeImmutable $endDate = null
    ) {}
}
