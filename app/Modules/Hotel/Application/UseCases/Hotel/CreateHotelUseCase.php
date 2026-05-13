<?php

namespace App\Modules\Hotel\Application\UseCases\Hotel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Modules\Hotel\Application\DTOs\Hotel\CreateHotelDTO;
use App\Modules\Hotel\Application\Services\HotelService;
use App\Modules\Hotel\Domain\Entities\Hotel;
use Throwable;

class CreateHotelUseCase
{
    public function __construct(
        private readonly HotelService $hotelService
    ) {}

    public function execute(CreateHotelDTO $dto): Hotel
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Iniciando caso de uso de creación de hotel', [
            'use_case_id' => $useCaseId,
            'function' => 'execute',
            'dto_name' => $dto->name,
            'dto_city_id' => $dto->cityId
        ]);

        try {
            $result = DB::transaction(function () use ($dto, $useCaseId) {
                Log::info('Iniciando transacción para creación de hotel', [
                    'use_case_id' => $useCaseId,
                    'transaction' => 'start'
                ]);

                $hotel = $this->hotelService->create($dto);

                Log::info('Transacción completada para creación de hotel', [
                    'use_case_id' => $useCaseId,
                    'transaction' => 'end',
                    'hotel_id' => $hotel->getId()
                ]);

                return $hotel;
            });

            Log::info('Caso de uso de creación de hotel completado', [
                'use_case_id' => $useCaseId,
                'result_hotel_id' => $result->getId()
            ]);

            return $result;
        } catch (Throwable $e) {
            Log::error('Error en caso de uso creación de hotel', [
                'use_case_id' => $useCaseId,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}