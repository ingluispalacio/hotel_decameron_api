<?php

namespace App\Modules\Hotel\Application\DTOs\City;

readonly class CreateCityDTO
{
    public function __construct(
        public string $name
    ) {}
}