<?php

namespace Core\Domain\Repositories;

use Core\Domain\Entities\PointEntity;

interface PointRepositoryInterface extends BaseRepositoryInterface
{
    public function save(PointEntity $point): PointEntity;

    public function findById(int $id): ?PointEntity;

    public function findByUserIdAndDate(int $userId, string $date): array;

    public function delete(int $id): bool;
}
