<?php

namespace Core\Application\Handlers;

use Core\Application\Commands\RegisterPointCommand;

class RegisterPointHandler
{
    public function __construct(private PointRepositoryInterface $pointRepository) {}

    public function handle(RegisterPointCommand $command): array
    {
        $point = $this->pointRepository->save([
            'user_id' => $command->userId,
            'registered_at' => $command->datetime,
            'latitude' => $command->latitude,
            'longitude' => $command->longitude,
        ]);

        return $point;
    }
}
