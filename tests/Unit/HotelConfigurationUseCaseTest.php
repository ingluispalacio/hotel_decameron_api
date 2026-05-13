<?php

namespace Tests\Unit;

use App\Modules\Hotel\Application\DTOs\HotelConfiguration\CreateHotelConfigurationDTO;
use App\Modules\Hotel\Application\DTOs\HotelConfiguration\UpdateHotelConfigurationDTO;
use App\Modules\Hotel\Application\UseCases\HotelConfiguration\CreateHotelConfigurationUseCase;
use App\Modules\Hotel\Application\UseCases\HotelConfiguration\UpdateHotelConfigurationUseCase;
use App\Modules\Hotel\Domain\Entities\Hotel;
use App\Modules\Hotel\Domain\Repositories\HotelConfigurationRepositoryInterface;
use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use DomainException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class HotelConfigurationUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_create_throws_when_total_rooms_exceed_max_rooms(): void
    {
        $hotel = new Hotel(
            'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
            'Decameron Test',
            'Test Address',
            'c1d2e3f4-a5b6-7890-abcd-ef1234567890',
            '900123457',
            10
        );

        $configurationRepository = Mockery::mock(HotelConfigurationRepositoryInterface::class);
        $configurationRepository
            ->shouldReceive('existsCombination')
            ->once()
            ->with('f1e2d3c4-b5a6-7890-abcd-ef1234567890', 'rt-123', 'accom-123', null)
            ->andReturn(false);
        $configurationRepository
            ->shouldReceive('sumQuantityByHotel')
            ->once()
            ->with('f1e2d3c4-b5a6-7890-abcd-ef1234567890', null)
            ->andReturn(8);
        $configurationRepository
            ->shouldReceive('save')
            ->never();

        $hotelRepository = Mockery::mock(HotelRepositoryInterface::class);
        $hotelRepository
            ->shouldReceive('findById')
            ->once()
            ->with('f1e2d3c4-b5a6-7890-abcd-ef1234567890')
            ->andReturn($hotel);

        $useCase = new CreateHotelConfigurationUseCase($configurationRepository, $hotelRepository);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('La cantidad total de habitaciones configuradas supera el máximo permitido para el hotel.');

        $useCase->execute(new CreateHotelConfigurationDTO(
            hotelId: 'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
            roomTypeId: 'rt-123',
            accommodationId: 'accom-123',
            quantity: 5,
        ));
    }

    public function test_create_throws_when_configuration_combination_already_exists(): void
    {
        $configurationRepository = Mockery::mock(HotelConfigurationRepositoryInterface::class);
        $configurationRepository
            ->shouldReceive('existsCombination')
            ->once()
            ->with('f1e2d3c4-b5a6-7890-abcd-ef1234567890', 'rt-123', 'accom-123', null)
            ->andReturn(true);
        $configurationRepository
            ->shouldReceive('save')
            ->never();

        $hotelRepository = Mockery::mock(HotelRepositoryInterface::class);
        $hotelRepository
            ->shouldReceive('findById')
            ->once()
            ->with('f1e2d3c4-b5a6-7890-abcd-ef1234567890')
            ->andReturn(new Hotel(
                'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
                'Decameron Test',
                'Test Address',
                'c1d2e3f4-a5b6-7890-abcd-ef1234567890',
                '900123457',
                20
            ));

        $useCase = new CreateHotelConfigurationUseCase($configurationRepository, $hotelRepository);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Ya existe una configuración para ese hotel, tipo de habitación y acomodación.');

        $useCase->execute(new CreateHotelConfigurationDTO(
            hotelId: 'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
            roomTypeId: 'rt-123',
            accommodationId: 'accom-123',
            quantity: 1,
        ));
    }

    public function test_update_throws_when_total_rooms_exceed_max_rooms(): void
    {
        $hotel = new Hotel(
            'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
            'Decameron Test',
            'Test Address',
            'c1d2e3f4-a5b6-7890-abcd-ef1234567890',
            '900123457',
            10
        );

        $configurationRepository = Mockery::mock(HotelConfigurationRepositoryInterface::class);
        $configurationRepository
            ->shouldReceive('findById')
            ->once()
            ->with('config-1')
            ->andReturn(new \App\Modules\Hotel\Domain\Entities\HotelConfiguration(
                'config-1',
                'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
                'rt-123',
                'accom-123',
                2
            ));
        $configurationRepository
            ->shouldReceive('existsCombination')
            ->once()
            ->with('f1e2d3c4-b5a6-7890-abcd-ef1234567890', 'rt-123', 'accom-123', 'config-1')
            ->andReturn(false);
        $configurationRepository
            ->shouldReceive('sumQuantityByHotel')
            ->once()
            ->with('f1e2d3c4-b5a6-7890-abcd-ef1234567890', 'config-1')
            ->andReturn(8);
        $configurationRepository
            ->shouldReceive('save')
            ->never();

        $hotelRepository = Mockery::mock(HotelRepositoryInterface::class);
        $hotelRepository
            ->shouldReceive('findById')
            ->once()
            ->with('f1e2d3c4-b5a6-7890-abcd-ef1234567890')
            ->andReturn($hotel);

        $useCase = new UpdateHotelConfigurationUseCase($configurationRepository, $hotelRepository);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('La cantidad total de habitaciones configuradas supera el máximo permitido para el hotel.');

        $useCase->execute(new UpdateHotelConfigurationDTO(
            id: 'config-1',
            hotelId: 'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
            roomTypeId: 'rt-123',
            accommodationId: 'accom-123',
            quantity: 5,
        ));
    }

    public function test_update_throws_when_configuration_combination_already_exists(): void
    {
        $configurationRepository = Mockery::mock(HotelConfigurationRepositoryInterface::class);
        $configurationRepository
            ->shouldReceive('findById')
            ->once()
            ->with('config-2')
            ->andReturn(new \App\Modules\Hotel\Domain\Entities\HotelConfiguration(
                'config-2',
                'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
                'rt-123',
                'accom-123',
                2
            ));
        $configurationRepository
            ->shouldReceive('existsCombination')
            ->once()
            ->with('f1e2d3c4-b5a6-7890-abcd-ef1234567890', 'rt-123', 'accom-123', 'config-2')
            ->andReturn(true);
        $configurationRepository
            ->shouldReceive('save')
            ->never();

        $hotelRepository = Mockery::mock(HotelRepositoryInterface::class);
        $hotelRepository
            ->shouldReceive('findById')
            ->once()
            ->with('f1e2d3c4-b5a6-7890-abcd-ef1234567890')
            ->andReturn(new Hotel(
                'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
                'Decameron Test',
                'Test Address',
                'c1d2e3f4-a5b6-7890-abcd-ef1234567890',
                '900123457',
                20
            ));

        $useCase = new UpdateHotelConfigurationUseCase($configurationRepository, $hotelRepository);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Ya existe una configuración para ese hotel, tipo de habitación y acomodación.');

        $useCase->execute(new UpdateHotelConfigurationDTO(
            id: 'config-2',
            hotelId: 'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
            roomTypeId: 'rt-123',
            accommodationId: 'accom-123',
            quantity: 4,
        ));
    }
}
