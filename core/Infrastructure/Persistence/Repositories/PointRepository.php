<?php

namespace Core\Infrastructure\Persistence\Repositories;

use Core\Domain\Entities\PointEntity;
use Core\Domain\Repositories\PointRepositoryInterface;
use Core\Infrastructure\Persistence\Models\Point;
use DateTimeImmutable;

class PointRepository extends EloquentBaseRepository implements PointRepositoryInterface
{
    public function __construct(Point $model)
    {
        parent::__construct($model);
    }

    public function save(PointEntity $point): PointEntity
    {
        $model = $point->getId() ? Point::find($point->getId()) : new Point();

        $model->user_id = $point->getUserId();
        $model->registered_at = $point->getRegisteredAt()->format('Y-m-d H:i:s');
        $model->latitude = $point->getLatitude();
        $model->longitude = $point->getLongitude();

        $model->save();

        return $this->mapToEntity($model);
    }

    public function findById(int $id): ?PointEntity
    {
        $model = Point::find($id);

        if (!$model) {
            return null;
        }

        return $this->mapToEntity($model);
    }

    public function findByUserIdAndDate(int $userId, string $date): array
    {
        $models = Point::where('user_id', $userId)
            ->whereDate('registered_at', $date)
            ->get();

        return $models->map(fn($model) => $this->mapToEntity($model))->toArray();
    }

    public function delete(int $id): bool
    {
        return Point::destroy($id) > 0;
    }

    private function mapToEntity(Point $model): PointEntity
    {
        return new PointEntity(
            userId: $model->user_id,
            registeredAt: new DateTimeImmutable($model->registered_at),
            latitude: $model->latitude,
            longitude: $model->longitude,
            id: $model->id,
        );
    }

}
