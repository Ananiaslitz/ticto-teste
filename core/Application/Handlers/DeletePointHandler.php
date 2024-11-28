<?php

namespace Core\Application\Handlers;

use Core\Application\Commands\DeletePointCommand;
use Core\Domain\Repositories\PointRepositoryInterface;

class DeletePointHandler
{
    public function __construct(private PointRepositoryInterface $pointRepository) {}

    public function handle(DeletePointCommand $command): void
    {
        $point = $this->pointRepository->findById($command->id);
        if (!$point) {
            throw new \DomainException('Ponto não encontrado.');
        }

        if ($point->getUserId() !== $command->userId) {
            throw new \DomainException('Usuário não autorizado a remover este ponto.');
        }

        $this->pointRepository->delete($command->id);
    }
}
