<?php

namespace Core\Application\Commands\Users;

use Core\Application\Contracts\Command;

class RegisterUserCommand implements Command
{
    public function __construct(
        public readonly string $name,
        public readonly string $cpf,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role,
        public readonly \DateTimeImmutable $birthDate,
        public readonly string $cep,
        public readonly array $address
    ) {}
}
