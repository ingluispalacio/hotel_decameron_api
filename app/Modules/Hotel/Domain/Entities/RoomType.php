<?php

namespace App\Modules\Hotel\Domain\Entities;

use App\Modules\Hotel\Domain\Enums\RoomTypeEnum;

class RoomType
{
    private string $id;
    private RoomTypeEnum $name;
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct(string $id, RoomTypeEnum $name)
    {
        $this->id = $id;
        $this->setName($name);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): RoomTypeEnum
    {
        return $this->name;
    }

    public function setName(RoomTypeEnum $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Room type name cannot be empty');
        }
        $this->name = $name;
    }

    public function softDelete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function restore(): void
    {
        $this->deletedAt = null;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }
    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name->value,
            'deleted_at' => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }
    
}
