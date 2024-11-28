<?php

namespace Core\Domain\ValueObjects;

use InvalidArgumentException;

class Address
{
    private string $cep;
    private string $street;
    private string $number;
    private string $city;
    private string $state;

    public function __construct(string $cep, string $street, string $number, string $city, string $state)
    {
        $this->cep = $this->validateCep($cep);
        $this->street = $street;
        $this->number = $this->validateNumber($number);
        $this->city = $city;
        $this->state = $state;
    }

    public static function fromCep(string $cep, string $number): self
    {
        $normalizedCep = preg_replace('/[^0-9]/', '', $cep);
        if (strlen($normalizedCep) !== 8) {
            throw new InvalidArgumentException('CEP inválido.');
        }

        $data = self::lookupCep($normalizedCep);

        return new self(
            $normalizedCep,
            $data['street'] ?? '',
            $number,
            $data['city'] ?? '',
            $data['state'] ?? ''
        );
    }

    private static function lookupCep(string $cep): array
    {
        return [
            'street' => 'Rua Exemplo',
            'city' => 'Cidade Exemplo',
            'state' => 'Estado Exemplo',
        ];
    }

    private function validateCep(string $cep): string
    {
        $normalized = preg_replace('/[^0-9]/', '', $cep);
        if (strlen($normalized) !== 8) {
            throw new InvalidArgumentException('CEP inválido.');
        }
        return $normalized;
    }

    private function validateNumber(string $number): string
    {
        if (empty($number)) {
            throw new InvalidArgumentException('Número do endereço não pode ser vazio.');
        }
        return $number;
    }

    public function toArray(): array
    {
        return [
            'cep' => $this->cep,
            'street' => $this->street,
            'number' => $this->number,
            'city' => $this->city,
            'state' => $this->state,
        ];
    }
}
