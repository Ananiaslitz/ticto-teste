<?php

namespace Core\Application\Handlers\Users;

use Core\Application\Commands\Users\UpdateEmployeeCommand;
use Core\Domain\Repositories\UserRepositoryInterface;
use Core\Domain\ValueObjects\Address;
use Core\Domain\ValueObjects\CPF;

class UpdateEmployeeHandler
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function handle(UpdateEmployeeCommand $command): void
    {
        $user = $this->userRepository->findById($command->id);

        if (!$user) {
            throw new \RuntimeException('Funcionário não encontrado.');
        }

        if ($command->name) {
            $user->setName($command->name);
        }

        if ($command->cpf) {
            $user->setCpf(new CPF($command->cpf));
        }

        if ($command->email) {
            $user->setEmail($command->email);
        }

        if ($command->role) {
            $user->setRole($command->role);
        }

        if ($command->birthDate) {
            $user->setBirthDate($command->birthDate);
        }

        if ($command->address) {
            $address = new Address(
                cep: $command->address['cep'],
                street: $command->address['street'],
                number: $command->address['number'],
                city: $command->address['city'],
                state: $command->address['state']
            );
            $user->setAddress($address);
        }

        $this->userRepository->save($user);
    }
}
