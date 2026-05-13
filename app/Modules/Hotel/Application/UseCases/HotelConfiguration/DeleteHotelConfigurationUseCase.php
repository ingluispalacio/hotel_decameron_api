<?php

namespace App\Modules\Hotel\Application\UseCases\HotelConfiguration;

use App\Modules\Hotel\Domain\Repositories\HotelConfigurationRepositoryInterface;
use DomainException;
use Illuminate\Support\Facades\Log;
use Throwable;

class DeleteHotelConfigurationUseCase
{
    public function __construct(
        private readonly HotelConfigurationRepositoryInterface $repository
    ) {}

    public function execute(string $id): void
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Iniciando eliminación de configuración de hotel', [
            'use_case_id' => $useCaseId,
            'configuration_id' => $id
        ]);

        try {
            // 1. Obtener la entidad de dominio
            $configuration = $this->repository->findById($id);
            if (!$configuration) {
                throw new DomainException("Hotel configuration with ID {$id} not found.");
            }

            // 2. Eliminar la entidad a través del repositorio
            $this->repository->delete($configuration);

            Log::info('Configuración eliminada exitosamente', [
                'use_case_id' => $useCaseId,
                'configuration_id' => $id
            ]);
        } catch (Throwable $e) {
            Log::error('Fallo en eliminación de configuración', [
                'use_case_id' => $useCaseId,
                'configuration_id' => $id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}