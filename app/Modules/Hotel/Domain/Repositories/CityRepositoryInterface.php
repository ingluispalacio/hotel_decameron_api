<?php

namespace App\Modules\Hotel\Domain\Repositories;

use App\Modules\Hotel\Domain\Entities\City;

interface CityRepositoryInterface
{
    /**
     * @return City[]
     */
    public function all();

    public function findById(string $id): ?City;

   public function save(City $city): void; 
}