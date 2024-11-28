<?php

namespace Core\Application\Commands\Users;

use Core\Application\Contracts\Command;

class ChangePasswordCommand implements Command
{
    public function __construct(
        public readonly int $userId,
        public readonly string $currentPassword,
        public readonly string $newPassword
    ) {}
}
