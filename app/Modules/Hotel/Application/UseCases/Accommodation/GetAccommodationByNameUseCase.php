<?php

namespace App\Modules\Hotel\Application\UseCases\Accommodation;

use App\Modules\Hotel\Domain\Repositories\AccommodationRepositoryInterface;
use App\Modules\Hotel\Domain\Entities\Accommodation;
use App\Modules\Hotel\Domain\Enums\AccommodationEnum;
use DomainException;
use Illuminate\Support\Facades\Log;
use Throwable;

class GetAccommodationByNameUseCase
{
    public function __construct(
        private readonly AccommodationRepositoryInterface $repository
    ) {}

    public function execute(string $name): ?Accommodation
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Buscando acomodación por nombre', [
            'use_case_id' => $useCaseId,
            'name'        => $name
        ]);

        try {
            // Convertir el string al enum, lanzar excepción si no es válido
            $enumName = AccommodationEnum::tryFrom($name);
            if ($enumName === null) {
                throw new DomainException("Invalid accommodation name: {$name}");
            }

            $accommodation = $this->repository->findByName($enumName);

            Log::info('Búsqueda completada', [
                'use_case_id' => $useCaseId,
                'found'       => $accommodation !== null,
                'id'          => $accommodation?->getId()
            ]);

            return $accommodation;
        } catch (Throwable $e) {
            Log::error('Error buscando acomodación', [
                'use_case_id' => $useCaseId,
                'error'       => $e->getMessage()
            ]);
            throw $e;
        }
    }
}