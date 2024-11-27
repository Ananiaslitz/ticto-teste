<?php

namespace Core\Application\Handlers;

use Core\Application\Commands\RegisterPointCommand;
use Core\Domain\Entities\PointEntity;
use Core\Domain\Repositories\PointRepositoryInterface;

class RegisterPointHandler
{
    public function __construct(private PointRepositoryInterface $pointRepository) {}

    public function handle(RegisterPointCommand $command): PointEntity
    {
        $point = new PointEntity(
            userId: $command->userId,
            registeredAt: $command->datetime,
            latitude: $command->latitude,
            longitude: $command->longitude
        );

        $lastPoint = $this->pointRepository->findLastPointByUser($command->userId);

        if ($lastPoint) {
            $point->validadeDeduplication($lastPoint);
        }


        return $this->pointRepository->save($point);
    }
}
