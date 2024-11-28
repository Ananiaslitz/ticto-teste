<?php

namespace Core\Application\Commands\Users;

use Core\Application\Contracts\Command;

class UpdateEmployeeCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?string $cpf = null,
        public readonly ?string $email = null,
        public readonly ?string $role = null,
        public readonly ?\DateTimeImmutable $birthDate = null,
        public readonly ?array $address = null
    ) {}
}
