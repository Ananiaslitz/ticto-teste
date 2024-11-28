<?php

namespace Core\Application\Handlers\Users;

use Core\Application\Commands\Users\ChangePasswordCommand;
use Core\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class ChangePasswordHandler
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function handle(ChangePasswordCommand $command): void
    {
        $user = $this->userRepository->findById($command->userId);

        if (!$user) {
            throw new RuntimeException('Usuário não encontrado.');
        }

        if (!Hash::check($command->currentPassword, $user->getPassword())) {
            throw new RuntimeException('Senha atual está incorreta.');
        }

        $user->changePassword(Hash::make($command->newPassword));
        $this->userRepository->save($user);
    }
}
