<?php

namespace App\Modules\Hotel\Domain\Entities;

use App\Modules\Hotel\Domain\Enums\AccommodationEnum;
use DateTimeImmutable;

class Accommodation
{
    private string $id;
    private AccommodationEnum $name;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct(string $id, AccommodationEnum $name, ?DateTimeImmutable $createdAt = null, ?DateTimeImmutable $updatedAt = null, ?DateTimeImmutable $deletedAt = null)
    {
        $this->id = $id;
        $this->setName($name);
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
        $this->deletedAt = $deletedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): AccommodationEnum
    {
        return $this->name;
    }

    public function setName(AccommodationEnum $name): void
    {
        $this->name = $name;
    }
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
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
            'name' => $this->name->value,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->isDeleted() ? $this->getDeletedAt()?->format('Y-m-d H:i:s') : null,
        ];
    }
}