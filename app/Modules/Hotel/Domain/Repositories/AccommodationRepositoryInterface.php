<?php

namespace App\Modules\Hotel\Domain\Repositories;

use App\Modules\Hotel\Domain\Entities\Accommodation;
use App\Modules\Hotel\Domain\Enums\AccommodationEnum;

interface AccommodationRepositoryInterface
{
    /**
     * @return Accommodation[]
     */
    public function all();
    public function findById(string $id): ?Accommodation;
    public function findByName(AccommodationEnum $name): ?Accommodation;
}