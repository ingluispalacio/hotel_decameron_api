<?php

namespace App\Modules\Hotel\Domain\Entities;

use App\Modules\Hotel\Domain\Enums\AccommodationEnum;

class Accommodation
{
    private string $id;
    private AccommodationEnum $name;
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct(string $id, AccommodationEnum $name)
    {
        $this->id = $id;
        $this->setName($name);
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
            'deleted_at' => $this->isDeleted() ? $this->getDeletedAt()?->format('Y-m-d H:i:s') : null,
        ];
    }
}