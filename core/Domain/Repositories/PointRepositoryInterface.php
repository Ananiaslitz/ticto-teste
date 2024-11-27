<?php

namespace Core\Domain\Repositories;

interface PointRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUserIdAndDate(int $userId, string $date): array;
}
