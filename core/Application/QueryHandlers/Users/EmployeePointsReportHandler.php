<?php

namespace Core\Application\QueryHandlers\Users;

use Core\Application\Queries\Users\EmployeePointsReportQuery;
use Core\Domain\Repositories\PointRepositoryInterface;

class EmployeePointsReportHandler
{
    public function __construct(private PointRepositoryInterface $pointRepository) {}

    public function handle(EmployeePointsReportQuery $query): array
    {
        return $this->pointRepository->findPointsWithFilters(
            startDate: $query->startDate,
            endDate: $query->endDate
        );
    }
}
