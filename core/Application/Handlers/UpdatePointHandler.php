<?php

namespace Core\Application\Handlers;

use Core\Application\Commands\UpdatePointCommand;
use Core\Domain\Entities\PointEntity;
use Core\Domain\Repositories\PointRepositoryInterface;

class UpdatePointHandler
{
    public function __construct(private PointRepositoryInterface $pointRepository) {}

    public function handle(UpdatePointCommand $command): PointEntity
    {
        $point = $this->pointRepository->findById($command->id);
        if (!$point) {
            throw new \DomainException('Ponto não encontrado.');
        }

        $updatedPoint = new PointEntity(
            userId: $command->userId,
            registeredAt: $command->datetime,
            latitude: $command->latitude,
            longitude: $command->longitude,
            id: $point->getId()
        );

        return $this->pointRepository->save($updatedPoint);
    }
}
