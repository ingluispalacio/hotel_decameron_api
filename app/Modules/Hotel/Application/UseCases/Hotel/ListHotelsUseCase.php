<?php

namespace App\Modules\Hotel\Application\UseCases\Hotel;

use App\Modules\Hotel\Application\DTOs\Hotel\HotelFiltersDTO;
use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use App\Shared\Domain\PaginatedResult;
use Illuminate\Support\Facades\Log;
use Throwable;

class ListHotelsUseCase
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository
    ) {}

    public function execute(HotelFiltersDTO $filters): PaginatedResult
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Listando hoteles con filtros', [
            'use_case_id' => $useCaseId,
            'per_page'    => $filters->perPage,
            'page'        => $filters->page,
            'search'      => $filters->search
        ]);

        try {
            // Llamada al repositorio con página y cantidad por página
            $paginatedResult = $this->hotelRepository->paginate(
                $filters->perPage,
                $filters->page
            );

            Log::info('Hoteles paginados obtenidos', [
                'use_case_id'   => $useCaseId,
                'total'         => $paginatedResult->total(),
                'per_page'      => $paginatedResult->perPage(),
                'current_page'  => $paginatedResult->currentPage(),
                'last_page'     => $paginatedResult->lastPage(),
                'items_count'   => count($paginatedResult->items())
            ]);

            return $paginatedResult;
        } catch (Throwable $e) {
            Log::error('Error al listar hoteles', [
                'use_case_id' => $useCaseId,
                'error'       => $e->getMessage()
            ]);
            throw $e;
        }
    }
}