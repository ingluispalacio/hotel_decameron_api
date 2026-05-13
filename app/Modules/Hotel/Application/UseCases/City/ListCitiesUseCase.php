<?php

namespace App\Modules\Hotel\Application\UseCases\City;

use App\Modules\Hotel\Domain\Repositories\CityRepositoryInterface;
use App\Modules\Hotel\Domain\Entities\City;
use Illuminate\Support\Facades\Log;
use Throwable;

class ListCitiesUseCase
{
    public function __construct(
        private readonly CityRepositoryInterface $repository
    ) {}

    /**
     * @return City[]
     */
    public function execute(): array
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Obteniendo todas las ciudades', [
            'use_case_id' => $useCaseId
        ]);

        try {
            $cities = $this->repository->all();

            Log::info('Ciudades obtenidas exitosamente', [
                'use_case_id' => $useCaseId,
                'count'       => count($cities)
            ]);

            return $cities;
        } catch (Throwable $e) {
            Log::error('Error al obtener ciudades', [
                'use_case_id' => $useCaseId,
                'error'       => $e->getMessage()
            ]);
            throw $e;
        }
    }
}