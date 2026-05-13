<?php

namespace App\Modules\Hotel\Application\UseCases\Accommodation;

use App\Modules\Hotel\Domain\Repositories\AccommodationRepositoryInterface;
use Illuminate\Support\Facades\Log;

class GetAccommodationByIdUseCase
{
    public function __construct(
        private readonly AccommodationRepositoryInterface $repository
    ) {}

    public function execute(string $id)
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Obteniendo acomodación por ID', [
            'use_case_id' => $useCaseId,
            'function' => 'execute',
            'accommodation_id' => $id
        ]);

        $accommodation = $this->repository->findById($id);

        Log::info('Acomodación obtenida del repositorio', [
            'use_case_id' => $useCaseId,
            'function' => 'execute',
            'found' => $accommodation !== null
        ]);

        return $accommodation;
    }
}