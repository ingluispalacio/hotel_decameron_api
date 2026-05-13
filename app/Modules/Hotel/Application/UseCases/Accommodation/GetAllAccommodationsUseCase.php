<?php

namespace App\Modules\Hotel\Application\UseCases\Accommodation;

use App\Modules\Hotel\Domain\Repositories\AccommodationRepositoryInterface;
use Illuminate\Support\Facades\Log;

class GetAllAccommodationsUseCase
{
    public function __construct(
        private readonly AccommodationRepositoryInterface $repository
    ) {}

    public function execute()
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Obteniendo todas las acomodaciones', [
            'use_case_id' => $useCaseId,
            'function' => 'execute'
        ]);

        $accommodations = $this->repository->all();

        Log::info('Acomodaciones obtenidas del repositorio', [
            'use_case_id' => $useCaseId,
            'function' => 'execute',
            'count' => count($accommodations)
        ]);

        return array_map(function ($accommodation) {
            return $accommodation->toArray();
        }, $accommodations);
    }
}