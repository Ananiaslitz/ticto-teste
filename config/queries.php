<?php

use Core\Application\Queries\ListPointsQuery;
use Core\Application\Queries\Users\EmployeePointsReportQuery;
use Core\Application\Queries\Users\ListEmployeesQuery;
use Core\Application\QueryHandlers\ListPointsHandler;
use Core\Application\QueryHandlers\Users\EmployeePointsReportHandler;
use Core\Application\QueryHandlers\Users\ListEmployeesHandler;

return [
    ListPointsQuery::class => ListPointsHandler::class,

    ListEmployeesQuery::class => ListEmployeesHandler::class,
    EmployeePointsReportQuery::class => EmployeePointsReportHandler::class,
];
