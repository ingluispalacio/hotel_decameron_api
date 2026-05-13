<?php

namespace App\Modules\Hotel\Domain\Repositories;

use App\Modules\Hotel\Domain\Entities\RoomType;
use App\Modules\Hotel\Domain\Enums\RoomTypeEnum;

interface RoomTypeRepositoryInterface
{
    /**
     * @return RoomType[]
     */
    public function all(): array;

    public function findById(string $id): ?RoomType;

    public function findByName(RoomTypeEnum $name): ?RoomType;

    // En lugar de create/update con arrays, usamos save:
    public function save(RoomType $roomType): void;

    // Recibe la entidad, no solo el ID
    public function delete(string $id): bool;
}