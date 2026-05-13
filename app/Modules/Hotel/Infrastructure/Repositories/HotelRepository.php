<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Infrastructure\Repositories;

use App\Modules\Hotel\Domain\Entities\Hotel as DomainHotel;
use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use App\Modules\Hotel\Infrastructure\Models\Hotel as EloquentHotel;
use App\Modules\Hotel\Infrastructure\Mappers\EloquentHotelMapper;
use App\Shared\Domain\PaginatedResult;

class HotelRepository implements HotelRepositoryInterface
{
    public function save(DomainHotel $hotel): void
    {
        $data = EloquentHotelMapper::toEloquent($hotel);
        $eloquent = EloquentHotel::find($hotel->getId()) ?? new EloquentHotel();
        $eloquent->fill($data);
        $eloquent->save();
    }

    public function findById(string $id): ?DomainHotel
    {
        $eloquent = EloquentHotel::withTrashed()->find($id);
        if (!$eloquent) {
            return null;
        }
        return EloquentHotelMapper::toDomain($eloquent);
    }

    public function findByName(string $name): ?DomainHotel
    {
        $eloquent = EloquentHotel::where('name', $name)->first();
        return $eloquent ? EloquentHotelMapper::toDomain($eloquent) : null;
    }

    public function findByNit(string $nit): ?DomainHotel
    {
        $eloquent = EloquentHotel::where('nit', $nit)->first();
        return $eloquent ? EloquentHotelMapper::toDomain($eloquent) : null;
    }

    public function delete(DomainHotel $hotel): void
    {
        $eloquent = EloquentHotel::find($hotel->getId());
        if ($eloquent) {
            $eloquent->delete();
        }
    }

    public function paginate(int $perPage = 10, int $page = 1): PaginatedResult
    {
        $query = EloquentHotel::query();
        // Si quieres incluir borrados suaves, usa withTrashed() según regla de negocio.
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        
        $items = array_map(
            fn($eloquent) => EloquentHotelMapper::toDomain($eloquent),
            $paginator->items()
        );
        
        return PaginatedResult::create(
            $items,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage()
        );
    }
}