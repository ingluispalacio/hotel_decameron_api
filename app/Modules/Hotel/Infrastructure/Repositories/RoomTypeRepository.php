<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Infrastructure\Repositories;

use App\Modules\Hotel\Domain\Entities\RoomType as DomainRoomType;
use App\Modules\Hotel\Domain\Enums\RoomTypeEnum;
use App\Modules\Hotel\Domain\Repositories\RoomTypeRepositoryInterface;
use App\Modules\Hotel\Infrastructure\Models\RoomType as EloquentRoomType;
use App\Modules\Hotel\Infrastructure\Mappers\EloquentRoomTypeMapper;

class RoomTypeRepository implements RoomTypeRepositoryInterface
{
    /**
     * @return DomainRoomType[]
     */
    public function all(): array
    {
        $eloquents = EloquentRoomType::withTrashed()->get();
        return array_map(
            fn($eloquent) => EloquentRoomTypeMapper::toDomain($eloquent),
            $eloquents->all()
        );
    }

    public function findById(string $id): ?DomainRoomType
    {
        $eloquent = EloquentRoomType::withTrashed()->find($id);
        return $eloquent ? EloquentRoomTypeMapper::toDomain($eloquent) : null;
    }

    public function findByName(RoomTypeEnum $name): ?DomainRoomType
    {
        $eloquent = EloquentRoomType::where('name', $name->value)->first();
        return $eloquent ? EloquentRoomTypeMapper::toDomain($eloquent) : null;
    }

    public function save(DomainRoomType $roomType): void
    {
        $data = EloquentRoomTypeMapper::toEloquent($roomType);
        $eloquent = EloquentRoomType::find($roomType->getId()) ?? new EloquentRoomType();
        $eloquent->fill($data);
        $eloquent->save();
    }

    public function delete(string $id): bool
    {
        $roomType = $this->findById($id);
        if (!$roomType) {
            return false;
        }
        $roomType->softDelete(); 
        $this->save($roomType);    
        return true;
    }
}