<?php

namespace App\Modules\Hotel\Application\UseCases\RoomType;

use App\Modules\Hotel\Domain\Repositories\RoomTypeRepositoryInterface;
use App\Modules\Hotel\Domain\Entities\RoomType;
use Illuminate\Support\Facades\Log;
use Throwable;

class ListRoomTypesUseCase
{
    public function __construct(
        private readonly RoomTypeRepositoryInterface $repository
    ) {}

    /**
     * @return RoomType[]
     */
    public function execute(): array
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Obteniendo todos los tipos de habitación', [
            'use_case_id' => $useCaseId
        ]);

        try {
            $roomTypes = $this->repository->all();

            Log::info('Tipos de habitación obtenidos exitosamente', [
                'use_case_id' => $useCaseId,
                'count'       => count($roomTypes)
            ]);

            return $roomTypes;
        } catch (Throwable $e) {
            Log::error('Error al obtener tipos de habitación', [
                'use_case_id' => $useCaseId,
                'error'       => $e->getMessage()
            ]);
            throw $e;
        }
    }
}