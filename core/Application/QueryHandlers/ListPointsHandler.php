<?php

namespace Core\Application\QueryHandlers;

use Core\Application\Queries\ListPointsQuery;
use Core\Domain\Repositories\PointRepositoryInterface;
use Core\Infrastructure\Policies\PointPolicy;
use Illuminate\Support\Facades\Auth;

class ListPointsHandler
{
    public function __construct(
        private PointRepositoryInterface $pointRepository,
        private PointPolicy $policy
    ) {}

    public function handle(ListPointsQuery $query): \Illuminate\Support\Collection
    {
        $user = Auth::user();

        if (!$this->policy->list($user, $query->userId)) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Acesso negado.');
        }

        return $this->pointRepository->findByFilters(
            $query->userId,
            $query->startDate,
            $query->endDate
        );
    }
}
