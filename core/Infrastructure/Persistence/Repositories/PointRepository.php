<?php

namespace Core\Infrastructure\Persistence\Repositories;

use Core\Domain\Repositories\PointRepositoryInterface;
use Core\Infrastructure\Persistence\Models\Point;

class PointRepository extends EloquentBaseRepository implements PointRepositoryInterface
{
    public function __construct(Point $model)
    {
        parent::__construct($model);
    }

    public function findByUserIdAndDate(int $userId, string $date): array
    {
        return $this->model
            ->where('user_id', $userId)
            ->whereDate('registered_at', $date)
            ->get()
            ->toArray();
    }
}
