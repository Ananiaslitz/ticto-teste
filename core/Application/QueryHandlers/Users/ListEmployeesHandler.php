<?php

namespace Core\Application\QueryHandlers\Users;


use Core\Application\Queries\Users\ListEmployeesQuery;
use Core\Domain\Repositories\UserRepositoryInterface;

class ListEmployeesHandler
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function handle(ListEmployeesQuery $query): array
    {
        return $this->userRepository->findByFilters($query->filters);
    }
}
