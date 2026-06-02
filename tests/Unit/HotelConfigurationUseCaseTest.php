<?php

namespace Tests\Unit;

use App\Modules\Hotel\Application\DTOs\HotelConfiguration\CreateHotelConfigurationDTO;
use App\Modules\Hotel\Application\DTOs\HotelConfiguration\UpdateHotelConfigurationDTO;
use App\Modules\Hotel\Application\Services\HotelConfigurationService;
use App\Modules\Hotel\Application\UseCases\HotelConfiguration\CreateHotelConfigurationUseCase;
use App\Modules\Hotel\Application\UseCases\HotelConfiguration\UpdateHotelConfigurationUseCase;
use DomainException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class HotelConfigurationUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_create_throws_when_total_rooms_exceed_max_rooms(): void
    {
        $dto = new CreateHotelConfigurationDTO(
            hotelId: 'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
            roomTypeId: 'rt-123',
            accommodationId: 'accom-123',
            quantity: 5,
        );

        $serviceMock = Mockery::mock(HotelConfigurationService::class);
        $serviceMock->shouldReceive('create')
            ->once()
            ->with($dto)
            ->andThrow(new DomainException('La cantidad total de habitaciones configuradas supera el máximo permitido para el hotel.'));

        $useCase = new CreateHotelConfigurationUseCase($serviceMock);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('La cantidad total de habitaciones configuradas supera el máximo permitido para el hotel.');

        $useCase->execute($dto);
    }

    public function test_create_throws_when_configuration_combination_already_exists(): void
    {
        $dto = new CreateHotelConfigurationDTO(
            hotelId: 'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
            roomTypeId: 'rt-123',
            accommodationId: 'accom-123',
            quantity: 1,
        );

        $serviceMock = Mockery::mock(HotelConfigurationService::class);
        $serviceMock->shouldReceive('create')
            ->once()
            ->with($dto)
            ->andThrow(new DomainException('Ya existe una configuración para ese hotel, tipo de habitación y acomodación.'));

        $useCase = new CreateHotelConfigurationUseCase($serviceMock);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Ya existe una configuración para ese hotel, tipo de habitación y acomodación.');

        $useCase->execute($dto);
    }

    public function test_update_throws_when_total_rooms_exceed_max_rooms(): void
    {
        $dto = new UpdateHotelConfigurationDTO(
            id: 'config-1',
            hotelId: 'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
            roomTypeId: 'rt-123',
            accommodationId: 'accom-123',
            quantity: 5,
        );

        $serviceMock = Mockery::mock(HotelConfigurationService::class);
        $serviceMock->shouldReceive('update')
            ->once()
            ->with($dto)
            ->andThrow(new DomainException('La cantidad total de habitaciones configuradas supera el máximo permitido para el hotel.'));

        $useCase = new UpdateHotelConfigurationUseCase($serviceMock);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('La cantidad total de habitaciones configuradas supera el máximo permitido para el hotel.');

        $useCase->execute($dto);
    }

    public function test_update_throws_when_configuration_combination_already_exists(): void
    {
        $dto = new UpdateHotelConfigurationDTO(
            id: 'config-2',
            hotelId: 'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
            roomTypeId: 'rt-123',
            accommodationId: 'accom-123',
            quantity: 4,
        );

        $serviceMock = Mockery::mock(HotelConfigurationService::class);
        $serviceMock->shouldReceive('update')
            ->once()
            ->with($dto)
            ->andThrow(new DomainException('Ya existe una configuración para ese hotel, tipo de habitación y acomodación.'));

        $useCase = new UpdateHotelConfigurationUseCase($serviceMock);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Ya existe una configuración para ese hotel, tipo de habitación y acomodación.');

        $useCase->execute($dto);
    }
}