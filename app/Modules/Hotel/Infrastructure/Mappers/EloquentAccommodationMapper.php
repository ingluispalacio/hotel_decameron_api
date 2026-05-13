<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Infrastructure\Mappers;

use App\Modules\Hotel\Domain\Entities\Accommodation as DomainAccommodation;
use App\Modules\Hotel\Domain\Enums\AccommodationEnum;
use App\Modules\Hotel\Infrastructure\Models\Accommodation as EloquentAccommodation;

class EloquentAccommodationMapper
{
    public static function toDomain(EloquentAccommodation $eloquent): DomainAccommodation
    {
        // Convertir string a enum
        $enumName = AccommodationEnum::from($eloquent->name);
        
        $accommodation = new DomainAccommodation(
            $eloquent->id,
            $enumName,
            $eloquent->created_at?->toDateTimeImmutable(),
            $eloquent->updated_at?->toDateTimeImmutable()
        );
        
        if ($eloquent->deleted_at !== null) {
            $accommodation->setDeletedAt($eloquent->deleted_at->toDateTimeImmutable());
        }
        return $accommodation;
    }

    public static function toEloquent(DomainAccommodation $accommodation): array
    {
        return [
            'id'         => $accommodation->getId(),
            'name'       => $accommodation->getName()->value,
            'created_at' => $accommodation->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at' => $accommodation->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'deleted_at' => $accommodation->getDeletedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}