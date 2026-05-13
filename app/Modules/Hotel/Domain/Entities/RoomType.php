<?php

namespace App\Modules\Hotel\Domain\Entities;

use App\Modules\Hotel\Domain\Enums\RoomTypeEnum;
use DateTimeImmutable;

class RoomType
{
    private string $id;
    private RoomTypeEnum $name;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct(
        string $id,
        RoomTypeEnum $name,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
        ?DateTimeImmutable $deletedAt = null
    ) {
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
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
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
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
