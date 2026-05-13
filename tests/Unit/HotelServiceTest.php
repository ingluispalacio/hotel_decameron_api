<?php

namespace Tests\Unit;

use App\Modules\Hotel\Application\DTOs\Hotel\CreateHotelDTO;
use App\Modules\Hotel\Application\Services\HotelService;
use App\Modules\Hotel\Domain\Entities\Hotel;
use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use DomainException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class HotelServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_create_throws_when_hotel_name_already_exists(): void
    {
        $existingHotel = new Hotel(
            '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
            'Decameron Cartagena',
            'Bocagrande Avenue',
            'c1d2e3f4-a5b6-7890-abcd-ef1234567890',
            '900123456',
            120
        );

        $hotelRepository = Mockery::mock(HotelRepositoryInterface::class);
        $hotelRepository
            ->shouldReceive('findByName')
            ->once()
            ->with('Decameron Cartagena')
            ->andReturn($existingHotel);
        $hotelRepository
            ->shouldReceive('findByNit')
            ->never();

        $service = new HotelService($hotelRepository);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('El nombre del hotel ya existe.');

        $service->create(new CreateHotelDTO(
            name: 'Decameron Cartagena',
            address: 'Bocagrande Avenue',
            cityId: 'c1d2e3f4-a5b6-7890-abcd-ef1234567890',
            nit: '900123456',
            maxRooms: 120,
        ));
    }

    public function test_create_throws_when_hotel_nit_already_exists(): void
    {
        $hotelRepository = Mockery::mock(HotelRepositoryInterface::class);
        $hotelRepository
            ->shouldReceive('findByName')
            ->once()
            ->with('Decameron Santa Marta')
            ->andReturnNull();
        $hotelRepository
            ->shouldReceive('findByNit')
            ->once()
            ->with('900123456')
            ->andReturn(new Hotel(
                'c56a4180-65aa-42ec-a945-5fd21dec0538',
                'Decameron Cartagena',
                'Bocagrande Avenue',
                'c1d2e3f4-a5b6-7890-abcd-ef1234567890',
                '900123456',
                100
            ));

        $service = new HotelService($hotelRepository);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('El NIT del hotel ya existe.');

        $service->create(new CreateHotelDTO(
            name: 'Decameron Santa Marta',
            address: 'Rodadero Beach',
            cityId: 'd1e2f3g4-h5i6-7890-abcd-ef1234567890',
            nit: '900123456',
            maxRooms: 80,
        ));
    }
}
