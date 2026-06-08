<?php

namespace App\Modules\Hotel\Application\UseCases\HotelConfiguration;

use App\Modules\Hotel\Domain\Repositories\HotelConfigurationRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowHotelConfigurationUseCase
{
    public function __construct(
        private readonly HotelConfigurationRepositoryInterface $repository
    ) {}

    /**
     * @return array{
     *     id: string,
     *     hotel_name: string,
     *     max_rooms: int,
     *     configurations: array<int, array{
     *         room_type: string,
     *         accommodation: string,
     *         quantity: int
     *     }>
     * }
     */
    public function execute(string $id): array
    {
        $hotel = $this->repository->findById($id);

        if (!$hotel) {
            throw new NotFoundHttpException('Hotel not found');
        }

        return $hotel;
    }
}
