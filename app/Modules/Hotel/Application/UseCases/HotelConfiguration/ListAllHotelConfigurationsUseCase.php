<?php
namespace App\Modules\Hotel\Application\UseCases\HotelConfiguration;

use App\Modules\Hotel\Domain\Repositories\HotelConfigurationRepositoryInterface;

class ListAllHotelConfigurationsUseCase
{
    public function __construct(
        private readonly HotelConfigurationRepositoryInterface $repository
    ) {}

    /**
     * @return array<int, array{hotel_name: string, configurations: array<int, array{room_type: string, accommodation: string, quantity: int}>}>
     */
    public function execute(): array
    {
        return $this->repository->findAll();
    }
}