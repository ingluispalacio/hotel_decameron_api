<?php

namespace App\Modules\Hotel\Infrastructure\Mappers;

use App\Modules\Hotel\Domain\Entities\City as DomainCity;
use App\Modules\Hotel\Infrastructure\Models\City as EloquentCity;

class EloquentCityMapper
{
    public static function toDomain(EloquentCity $eloquent): DomainCity
    {
        $city = new DomainCity($eloquent->id, $eloquent->name);
        if ($eloquent->deleted_at !== null) {
            $city->setDeletedAt(new \DateTimeImmutable($eloquent->deleted_at));
        }
        return $city;
    }

    public static function toEloquent(DomainCity $city): array
    {
        return [
            'id' => $city->getId(),
            'name' => $city->getName(),
             'deleted_at'      => $city->isDeleted() ? $city->getDeletedAt()?->format('Y-m-d H:i:s') : null,
        ];
    }
}