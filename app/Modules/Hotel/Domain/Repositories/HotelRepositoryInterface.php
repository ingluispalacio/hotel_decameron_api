<?php

namespace App\Modules\Hotel\Domain\Repositories;

use App\Modules\Hotel\Domain\Entities\Hotel;
use App\Shared\Domain\PaginatedResult;

interface HotelRepositoryInterface
{
    public function save(Hotel $hotel): void;
    public function findById(string $id): ?Hotel;
    public function findByName(string $name): ?Hotel;
    public function delete(Hotel $hotel): void;
    public function findByNit(string $nit): ?Hotel;
    /**
     * @return PaginatedResult<Hotel>
     */
    public function paginate(int $perPage = 10, int $page = 1): PaginatedResult;
}
