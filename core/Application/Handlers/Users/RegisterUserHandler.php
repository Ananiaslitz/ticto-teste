<?php

namespace Core\Application\Handlers\Users;

use Core\Application\Commands\Users\RegisterUserCommand;
use Core\Domain\Entities\UserEntity;
use Core\Domain\Repositories\UserRepositoryInterface;
use Core\Domain\ValueObjects\CPF;
use Core\Domain\ValueObjects\Address;

class RegisterUserHandler
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function handle(RegisterUserCommand $command): UserEntity
    {
        if ($this->userRepository->cpfExists(new CPF($command->cpf))) {
            throw new \DomainException('CPF já cadastrado.');
        }

        $address = new Address(
            cep: $command->cep,
            street: $command->address['street'],
            city: $command->address['city'],
            number: $command->address['number'],
            state: $command->address['state']
        );

        $user = new UserEntity(
            name: $command->name,
            cpf: new CPF($command->cpf),
            email: $command->email,
            password: password_hash($command->password, PASSWORD_BCRYPT),
            role: $command->role,
            birthDate: $command->birthDate,
            address: $address
        );

        return $this->userRepository->save($user);
    }
}
