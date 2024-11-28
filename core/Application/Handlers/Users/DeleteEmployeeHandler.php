<?php

namespace Core\Application\Handlers\Users;

use Core\Application\Commands\Users\DeleteEmployeeCommand;
use Core\Domain\Repositories\UserRepositoryInterface;

class DeleteEmployeeHandler
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function handle(DeleteEmployeeCommand $command): void
    {
        $user = $this->userRepository->findById($command->id);

        if (!$user) {
            throw new \RuntimeException('Funcionário não encontrado.');
        }

        $this->userRepository->delete($user);
    }
}
