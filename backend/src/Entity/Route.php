<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'routes')]
class Route
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, length: 36)]
    private ?string $id = null;

    #[ORM\Column(name: 'from_station_id', type: Types::STRING, length: 10)]
    private string $fromStationId;

    #[ORM\Column(name: 'to_station_id', type: Types::STRING, length: 10)]
    private string $toStationId;

    #[ORM\Column(name: 'analytic_code', type: Types::STRING, length: 50)]
    private string $analyticCode;

    #[ORM\Column(name: 'distance_km', type: Types::FLOAT)]
    private float $distanceKm;

    #[ORM\Column(type: Types::JSON)]
    private array $path = [];

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getFromStationId(): string
    {
        return $this->fromStationId;
    }

    public function setFromStationId(string $fromStationId): self
    {
        $this->fromStationId = $fromStationId;
        return $this;
    }

    public function getToStationId(): string
    {
        return $this->toStationId;
    }

    public function setToStationId(string $toStationId): self
    {
        $this->toStationId = $toStationId;
        return $this;
    }

    public function getAnalyticCode(): string
    {
        return $this->analyticCode;
    }

    public function setAnalyticCode(string $analyticCode): self
    {
        $this->analyticCode = $analyticCode;
        return $this;
    }

    public function getDistanceKm(): float
    {
        return $this->distanceKm;
    }

    public function setDistanceKm(float $distanceKm): self
    {
        $this->distanceKm = $distanceKm;
        return $this;
    }

    public function getPath(): array
    {
        return $this->path;
    }

    public function setPath(array $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fromStationId' => $this->fromStationId,
            'toStationId' => $this->toStationId,
            'analyticCode' => $this->analyticCode,
            'distanceKm' => $this->distanceKm,
            'path' => $this->path,
            'createdAt' => $this->createdAt->format('c'),
        ];
    }
}

