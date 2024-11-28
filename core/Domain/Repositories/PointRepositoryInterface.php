<?php

namespace Core\Domain\Repositories;

use Core\Domain\Entities\PointEntity;

interface PointRepositoryInterface extends BaseRepositoryInterface
{
    public function save(PointEntity $point): PointEntity;
    public function findLastPointByUser(int $userId): ?PointEntity;

    public function findById(int $id): ?PointEntity;

    public function findByUserIdAndDate(int $userId, string $date): array;

    public function findPointsWithFilters(?\DateTimeImmutable $startDate, ?\DateTimeImmutable $endDate): array;

    public function findByFilters(
        int $userId,
        ?\DateTimeImmutable $startDate = null,
        ?\DateTimeImmutable $endDate = null
    ): \Illuminate\Support\Collection;

    public function delete(int $id): bool;
}
