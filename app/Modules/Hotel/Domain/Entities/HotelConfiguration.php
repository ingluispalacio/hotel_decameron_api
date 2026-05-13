<?php

namespace App\Modules\Hotel\Domain\Entities;

class HotelConfiguration
{
    private string $id;
    private string $hotelId;
    private string $roomTypeId;
    private string $accommodationId;
    private int $quantity;
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct(
        string $id,
        string $hotelId,
        string $roomTypeId,
        string $accommodationId,
        int $quantity
    ) {
        $this->id = $id;
        $this->hotelId = $hotelId;
        $this->roomTypeId = $roomTypeId;
        $this->accommodationId = $accommodationId;
        $this->setQuantity($quantity);
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }
    public function getHotelId(): string
    {
        return $this->hotelId;
    }
    public function getRoomTypeId(): string
    {
        return $this->roomTypeId;
    }
    public function getAccommodationId(): string
    {
        return $this->accommodationId;
    }
    public function getQuantity(): int
    {
        return $this->quantity;
    }
    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setQuantity(int $quantity): void
    {
        if ($quantity < 0) {
            throw new \InvalidArgumentException('Quantity cannot be negative');
        }
        $this->quantity = $quantity;
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'hotel_id' => $this->hotelId,
            'room_type_id' => $this->roomTypeId,
            'accommodation_id' => $this->accommodationId,
            'quantity' => $this->quantity,
            'deleted_at' => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
