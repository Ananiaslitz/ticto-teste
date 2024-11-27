<?php

namespace Core\Domain\Entities;

use DateTimeImmutable;

class PointEntity implements \JsonSerializable
{
    private ?int $id;
    private int $userId;
    private DateTimeImmutable $registeredAt;
    private ?float $latitude;
    private ?float $longitude;

    public function __construct(
        int $userId,
        DateTimeImmutable $registeredAt,
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->registeredAt = $registeredAt;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRegisteredAt(): DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'registered_at' => $this->registeredAt,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }

    public function validadeDeduplication(PointEntity $lastPoint): void
    {
        if (
            $this->userId === $lastPoint->getUserId() &&
            abs($this->registeredAt->getTimestamp() - $lastPoint->getRegisteredAt()->getTimestamp()) < 600
        ) {
            throw new \DomainException('O ponto não pode ser registrado duas vezes dentro de 10 minutos.');
        }
    }
}
