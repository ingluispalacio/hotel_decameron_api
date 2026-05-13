<?php

namespace App\Modules\Hotel\Domain\Entities;

class City
{
    private string $id;
    private string $name;
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->setName($name);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
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
    public function getDeletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'deleted_at' => $this->isDeleted() ? $this->getDeletedAt()?->format('Y-m-d H:i:s') : null,
        ];
    }
}