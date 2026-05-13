<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Infrastructure\Repositories;

use App\Modules\Hotel\Domain\Entities\City as DomainCity;
use App\Modules\Hotel\Domain\Repositories\CityRepositoryInterface;
use App\Modules\Hotel\Infrastructure\Models\City as EloquentCity;
use App\Modules\Hotel\Infrastructure\Mappers\EloquentCityMapper;

class CityRepository implements CityRepositoryInterface
{
    public function save(DomainCity $city): void
    {
        $data = EloquentCityMapper::toEloquent($city);
        $eloquent = EloquentCity::find($city->getId()) ?? new EloquentCity();
        $eloquent->fill($data);
        $eloquent->save();
    }

    public function findById(string $id): ?DomainCity
    {
        $eloquent = EloquentCity::withTrashed()->find($id);
        if (!$eloquent) {
            return null;
        }
        return EloquentCityMapper::toDomain($eloquent);
    }

    /**
     * @return DomainCity[]
     */
    public function all(): array
    {
        $eloquents = EloquentCity::withTrashed()->get();
        return array_map(
            fn($eloquent) => EloquentCityMapper::toDomain($eloquent),
            $eloquents->all()
        );
    }

    public function delete(DomainCity $city): void
    {
        $eloquent = EloquentCity::find($city->getId());
        if ($eloquent) {
            $eloquent->delete();
        }
    }
}