<?php

namespace Core\Domain\Repositories;

use Core\Domain\Entities\UserEntity;
use Core\Domain\ValueObjects\CPF;

interface UserRepositoryInterface
{
    public function save(UserEntity $user): UserEntity;

    public function findById(int $id): ?UserEntity;

    public function findByCPF(CPF $cpf): ?UserEntity;

    public function cpfExists(CPF $cpf): bool;

    public function findByFilters(array $filters): array;

    public function delete(UserEntity $user): void;
}
