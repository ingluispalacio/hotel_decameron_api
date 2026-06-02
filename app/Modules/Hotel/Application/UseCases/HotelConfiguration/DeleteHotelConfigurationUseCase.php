<?php

namespace App\Modules\Hotel\Application\UseCases\HotelConfiguration;

use App\Modules\Hotel\Application\Services\HotelConfigurationService;


class DeleteHotelConfigurationUseCase
{
    public function __construct(
        private readonly HotelConfigurationService $service
    ) {}

    public function execute(string $id): void
    {
        $this->service->delete($id);
    }
}