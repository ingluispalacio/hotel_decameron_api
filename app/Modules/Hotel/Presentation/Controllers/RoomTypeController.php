<?php

namespace App\Modules\Hotel\Presentation\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use App\Modules\Hotel\Application\UseCases\RoomType\ListRoomTypesUseCase;
use App\Modules\Hotel\Application\UseCases\RoomType\GetRoomTypeByIdUseCase;

/**
 * @authenticated
 * @group Room Types
 *
 * APIs for managing hotel room types.
 */
class RoomTypeController extends Controller
{
    public function __construct(
        private readonly ListRoomTypesUseCase $listRoomTypesUseCase,
        private readonly GetRoomTypeByIdUseCase $getRoomTypeByIdUseCase
    ) {}

    /**
     * List room types
     *
     * Returns all available room types.
     *
     * @response 200 [
     *   {
     *     "id": "b1c2d3e4-f5a6-7890-abcd-ef1234567890",
     *     "name": "STANDARD",
     *     "created_at": "2026-05-12T10:00:00.000000Z",
     *     "updated_at": "2026-05-12T10:00:00.000000Z"
     *   },
     *   {
     *     "id": "c2d3e4f5-a6b7-8901-abcd-ef9876543210",
     *     "name": "JUNIOR",
     *     "created_at": "2026-05-12T10:00:00.000000Z",
     *     "updated_at": "2026-05-12T10:00:00.000000Z"
     *   }
     * ]
     */
    public function index(): JsonResponse
    {
        $requestId = uniqid('req_', true);

        Log::info('Iniciando solicitud de obtención de tipos de habitación', [
            'request_id' => $requestId,
            'function' => 'index',
            // 'user_id' => auth()->id()
        ]);

        $roomTypes = $this->listRoomTypesUseCase->execute();

        Log::info('Tipos de habitación obtenidos exitosamente', [
            'request_id' => $requestId,
            'function' => 'index',
            'count' => count($roomTypes)
        ]);

        return response()->json(array_map(function ($roomType) {
            return $roomType->toArray();
        }, $roomTypes));
    }

    /**
     * Get room type by ID
     *
     * Returns a room type by its UUID.
     *
     * @urlParam id uuid required Room type ID.
     * Example: b1c2d3e4-f5a6-7890-abcd-ef1234567890
     *
     * @response 200 {
     *   "id": "b1c2d3e4-f5a6-7890-abcd-ef1234567890",
     *   "name": "STANDARD",
     *   "created_at": "2026-05-12T10:00:00.000000Z",
     *   "updated_at": "2026-05-12T10:00:00.000000Z"
     * }
     *
     * @response 404 {
     *   "message": "Room type not found."
     * }
     */
    public function show(string $id): JsonResponse
    {
        $requestId = uniqid('req_', true);

        Log::info('Iniciando solicitud de obtención de tipo de habitación por ID', [
            'request_id' => $requestId,
            'function' => 'show',
            'room_type_id' => $id
        ]);

        $roomType = $this->getRoomTypeByIdUseCase->execute($id);

        if (!$roomType) {
            Log::warning('Tipo de habitación no encontrado', [
                'request_id' => $requestId,
                'function' => 'show',
                'room_type_id' => $id
            ]);

            return response()->json([
                'message' => 'Room type not found.'
            ], 404);
        }

        Log::info('Tipo de habitación obtenido exitosamente', [
            'request_id' => $requestId,
            'function' => 'show',
            'room_type_id' => $roomType->getId()
        ]);

        return response()->json($roomType->toArray());
    }
}