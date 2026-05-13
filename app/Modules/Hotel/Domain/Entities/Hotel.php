<?php

namespace App\Modules\Hotel\Domain\Entities;

class Hotel
{
    private string $id;
    private string $name;
    private string $address;
    private string $cityId;
    private string $nit;
    private int $maxRooms;
    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * @var HotelConfiguration[] 
     * (esto normalmente se carga a través de un repositorio, no se pasa en el constructor)
     */
    private array $configurations = [];

    public function __construct(
        string $id,
        string $name,
        string $address,
        string $cityId,
        string $nit,
        int $maxRooms
    ) {
        $this->id = $id;
        $this->setName($name);
        $this->setAddress($address);
        $this->cityId = $cityId;
        $this->setNit($nit);
        $this->setMaxRooms($maxRooms);
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getAddress(): string
    {
        return $this->address;
    }
    public function getCityId(): string
    {
        return $this->cityId;
    }
    public function getNit(): string
    {
        return $this->nit;
    }
    public function getMaxRooms(): int
    {
        return $this->maxRooms;
    }
    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    /**
     * @return HotelConfiguration[]
     */
    public function getConfigurations(): array
    {
        return $this->configurations;
    }

    public function setName(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Hotel name cannot be empty');
        }
        $this->name = $name;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function setNit(string $nit): void
    {
        // Por ejemplo: validar formato NIT
        $this->nit = $nit;
    }
    public function setDeletedAt(?\DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
    public function setMaxRooms(int $maxRooms): void
    {
        if ($maxRooms <= 0) {
            throw new \InvalidArgumentException('Max rooms must be positive');
        }
        $this->maxRooms = $maxRooms;
    }


    public function addConfiguration(HotelConfiguration $configuration): void
    {

        if ($configuration->getHotelId() !== $this->id) {
            throw new \LogicException('Configuration hotel ID mismatch');
        }
        $this->configurations[] = $configuration;
    }

    public function softDelete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function restore(): void
    {
        $this->deletedAt = null;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city_id' => $this->cityId,
            'nit' => $this->nit,
            'max_rooms' => $this->maxRooms,
            'deleted_at' => $this->isDeleted() ? $this->deletedAt?->format('Y-m-d H:i:s') : null,
        ];
    }
}
