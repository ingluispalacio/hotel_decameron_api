<?php

namespace App\Modules\Hotel\Application\UseCases\RoomType;

use App\Modules\Hotel\Domain\Repositories\RoomTypeRepositoryInterface;
use App\Modules\Hotel\Domain\Entities\RoomType;
use Illuminate\Support\Facades\Log;
use Throwable;

class GetRoomTypeByIdUseCase
{
    public function __construct(
        private readonly RoomTypeRepositoryInterface $repository
    ) {}

    public function execute(string $id): ?RoomType
    {
        $useCaseId = uniqid('uc_', true);

        Log::info('Buscando tipo de habitación por ID', [
            'use_case_id' => $useCaseId,
            'room_type_id' => $id
        ]);

        try {
            $roomType = $this->repository->findById($id);

            if ($roomType === null) {
                Log::info('Tipo de habitación no encontrado', [
                    'use_case_id' => $useCaseId,
                    'room_type_id' => $id
                ]);
            } else {
                Log::info('Tipo de habitación encontrado', [
                    'use_case_id' => $useCaseId,
                    'room_type_id' => $roomType->getId(),
                    'name' => $roomType->getName()
                ]);
            }

            return $roomType;
        } catch (Throwable $e) {
            Log::error('Error al buscar tipo de habitación por ID', [
                'use_case_id' => $useCaseId,
                'room_type_id' => $id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}