<?php

namespace App\Modules\Hotel\Application\DTOs\Hotel;

readonly class HotelFiltersDTO
{
    public function __construct(
        public ?string $search = null,
        public int $perPage = 10,
        public int $page = 1
    ) {}
}