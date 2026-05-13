<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Infrastructure\Repositories;

use App\Modules\Hotel\Domain\Entities\Accommodation as DomainAccommodation;
use App\Modules\Hotel\Domain\Repositories\AccommodationRepositoryInterface;
use App\Modules\Hotel\Domain\Enums\AccommodationEnum;
use App\Modules\Hotel\Infrastructure\Models\Accommodation as EloquentAccommodation;
use App\Modules\Hotel\Infrastructure\Mappers\EloquentAccommodationMapper;

class AccommodationRepository implements AccommodationRepositoryInterface
{
    /**
     * @return DomainAccommodation[]
     */
    public function all(): array
    {
        $eloquents = EloquentAccommodation::withTrashed()->get();
        return array_map(
            fn($eloquent) => EloquentAccommodationMapper::toDomain($eloquent),
            $eloquents->all()
        );
    }

    public function findById(string $id): ?DomainAccommodation
    {
        $eloquent = EloquentAccommodation::withTrashed()->find($id);
        return $eloquent ? EloquentAccommodationMapper::toDomain($eloquent) : null;
    }

    public function findByName(AccommodationEnum $name): ?DomainAccommodation
    {
        $eloquent = EloquentAccommodation::where('name', $name->value)->first();
        return $eloquent ? EloquentAccommodationMapper::toDomain($eloquent) : null;
    }
}