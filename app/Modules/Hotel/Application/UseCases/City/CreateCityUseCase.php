<?php

namespace App\Modules\Hotel\Application\UseCases\City;

use App\Modules\Hotel\Application\DTOs\City\CreateCityDTO;
use App\Modules\Hotel\Application\Mappers\CityEntityMapper;
use App\Modules\Hotel\Domain\Repositories\CityRepositoryInterface;
use App\Modules\Hotel\Domain\Entities\City;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateCityUseCase
{
    public function __construct(
        private readonly CityRepositoryInterface $repository
    ) {}

    public function execute(CreateCityDTO $dto): City
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Iniciando creación de ciudad', [
            'use_case_id' => $useCaseId,
            'city_name'   => $dto->name
        ]);

        try {
            // 1. Crear la entidad mediante el mapper (genera nuevo UUID)
            $city = CityEntityMapper::toEntityFromCreateDTO($dto);

            Log::info('Entidad ciudad creada', [
                'use_case_id' => $useCaseId,
                'city_id'     => $city->getId()
            ]);

            // 2. Persistir a través del repositorio
            $this->repository->save($city);

            Log::info('Ciudad creada exitosamente', [
                'use_case_id' => $useCaseId,
                'city_id'     => $city->getId(),
                'city_name'   => $city->getName()
            ]);

            return $city;
        } catch (Throwable $e) {
            Log::error('Error al crear ciudad', [
                'use_case_id' => $useCaseId,
                'error'       => $e->getMessage()
            ]);
            throw $e;
        }
    }
}