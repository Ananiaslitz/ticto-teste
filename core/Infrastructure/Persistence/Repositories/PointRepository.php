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

    public function findLastPointByUser(int $userId): ?PointEntity
    {
        $model = $this->model
            ->where('user_id', $userId)
            ->orderBy('registered_at', 'desc')
            ->first();

        if (!$model) {
            return null;
        }

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

    public function findByFilters(
        int $userId,
        ?\DateTimeImmutable $startDate = null,
        ?\DateTimeImmutable $endDate = null
    ): \Illuminate\Support\Collection
    {
        $query = $this->model->where('user_id', $userId);

        if ($startDate) {
            $query->whereDate('registered_at', '>=', $startDate->format('Y-m-d'));
        }

        if ($endDate) {
            $query->whereDate('registered_at', '<=', $endDate->format('Y-m-d'));
        }

        return $query->get();
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    public function findPointsWithFilters(?\DateTimeImmutable $startDate, ?\DateTimeImmutable $endDate): array
    {
        $query = $this->model->query()
            ->join('users as employees', 'points.user_id', '=', 'employees.id')
            ->join('users as managers', 'managers.role', '=', \DB::raw('"admin"')) // Relaciona com usuários admins
            ->select([
                'points.id as point_id',
                'employees.name as employee_name',
                'employees.role as employee_role',
                \DB::raw('TIMESTAMPDIFF(YEAR, employees.birth_date, CURDATE()) as employee_age'),
                'managers.name as manager_name',
                'points.registered_at as point_date',
            ]);

        if ($startDate) {
            $query->where('points.registered_at', '>=', $startDate->format('Y-m-d H:i:s'));
        }

        if ($endDate) {
            $query->where('points.registered_at', '<=', $endDate->format('Y-m-d H:i:s'));
        }

        return $query->get()->toArray();
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
