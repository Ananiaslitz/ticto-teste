<?php

namespace Core\Domain\Entities;

use Core\Domain\ValueObjects\Address;
use Core\Domain\ValueObjects\CPF;

class UserEntity
{
    private ?int $id;
    private string $name;
    private CPF $cpf;
    private string $email;
    private string $password;
    private string $role;
    private \DateTimeImmutable $birthDate;
    private Address $address;

    public function __construct(
        string $name,
        CPF $cpf,
        string $email,
        string $password,
        string $role,
        \DateTimeImmutable $birthDate,
        Address $address,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->cpf = $cpf;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->birthDate = $birthDate;
        $this->address = $address;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCpf(): CPF
    {
        return $this->cpf;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getBirthDate(): \DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function changePassword(string $newPassword): void
    {
        $this->password = $newPassword;
    }

    public function setId(?int $id): UserEntity
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): UserEntity
    {
        $this->name = $name;
        return $this;
    }

    public function setCpf(CPF $cpf): UserEntity
    {
        $this->cpf = $cpf;
        return $this;
    }

    public function setEmail(string $email): UserEntity
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): UserEntity
    {
        $this->password = $password;
        return $this;
    }

    public function setRole(string $role): UserEntity
    {
        $this->role = $role;
        return $this;
    }

    public function setBirthDate(\DateTimeImmutable $birthDate): UserEntity
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function setAddress(Address $address): UserEntity
    {
        $this->address = $address;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cpf' => (string)$this->cpf,
            'email' => $this->email,
            'role' => $this->role,
            'birthDate' => $this->birthDate->format('Y-m-d'),
            'address' => $this->address->toArray(),
        ];
    }
}
