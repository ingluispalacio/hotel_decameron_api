<?php

namespace App\Modules\Hotel\Presentation\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use App\Modules\Hotel\Presentation\Requests\HotelConfiguration\StoreHotelConfigurationRequest;
use App\Modules\Hotel\Presentation\Requests\HotelConfiguration\UpdateHotelConfigurationRequest;

use App\Modules\Hotel\Application\DTOs\HotelConfiguration\CreateHotelConfigurationDTO;
use App\Modules\Hotel\Application\DTOs\HotelConfiguration\UpdateHotelConfigurationDTO;

use App\Modules\Hotel\Application\UseCases\HotelConfiguration\CreateHotelConfigurationUseCase;
use App\Modules\Hotel\Application\UseCases\HotelConfiguration\UpdateHotelConfigurationUseCase;
use App\Modules\Hotel\Application\UseCases\HotelConfiguration\DeleteHotelConfigurationUseCase;
use App\Modules\Hotel\Application\UseCases\HotelConfiguration\ListAllHotelConfigurationsUseCase;
use App\Modules\Hotel\Application\UseCases\HotelConfiguration\ShowHotelConfigurationUseCase;

/**
 * @authenticated
 * @group Hotel Configurations
 *
 * APIs for managing hotel room configurations.
 */
class HotelConfigurationController extends Controller
{
    public function __construct(
        private readonly CreateHotelConfigurationUseCase $createHotelConfigurationUseCase,
        private readonly UpdateHotelConfigurationUseCase $updateHotelConfigurationUseCase,
        private readonly DeleteHotelConfigurationUseCase $deleteHotelConfigurationUseCase,
        private readonly ListAllHotelConfigurationsUseCase $listAllHotelConfigurationsUseCase,
        private readonly ShowHotelConfigurationUseCase $showHotelConfigurationUseCase
    ) {}


    /**
     * List all hotels with their configurations
     *
     * Returns a list of all hotels, each containing its configurations with names (not IDs).
     *
     * @response 200 [
     *   {
     *     "hotel_name": "Decameron Cartagena",
     *     "configurations": [
     *       {
     *         "room_type": "Standard",
     *         "accommodation": "Doble",
     *         "quantity": 10
     *       },
     *       {
     *         "room_type": "Junior",
     *         "accommodation": "Triple",
     *         "quantity": 5
     *       }
     *     ]
     *   },
     *   {
     *     "hotel_name": "Decameron Santa Marta",
     *     "configurations": [...]
     *   }
     * ]
     * @response 401 {"message": "Unauthenticated"}
     */
    public function listAll(): JsonResponse
    {
        return response()->json($this->listAllHotelConfigurationsUseCase->execute());
    }

    /**
     * Show hotel configurations
     *
     * Returns a single hotel with all its configurations.
     *
     * @urlParam id string required Hotel ID.
     *
     * @response 200 {
     *   "id": "1",
     *   "hotel_name": "Decameron Cartagena",
     *   "max_rooms": 42,
     *   "configurations": [
     *     {
     *       "room_type": "Standard",
     *       "accommodation": "Doble",
     *       "quantity": 10
     *     }
     *   ]
     * }
     *
     * @response 404 {
     *   "message": "Hotel not found"
     * }
     */
    public function show(string $id): JsonResponse
    {
        return response()->json(
            $this->showHotelConfigurationUseCase->execute($id)
        );
    }

    /**
     * Create hotel configuration
     *
     * Creates a new hotel room configuration.
     *
     * @response 201 {
     *   "id": "a1b2c3d4-e5f6-7890-abcd-ef1234567890",
     *   "hotel_id": "f1e2d3c4-b5a6-7890-abcd-ef1234567890",
     *   "room_type_id": "b1c2d3e4-f5a6-7890-abcd-ef1234567890",
     *   "accommodation_id": "c1d2e3f4-a5b6-7890-abcd-ef1234567890",
     *   "quantity": 5,
     *   "created_at": "2026-05-12T10:00:00.000000Z",
     *   "updated_at": "2026-05-12T10:00:00.000000Z"
     * }
     * @response 422 {"message": "The given data was invalid.", "errors": {...}}
     * @response 404 {"message": "Hotel not found"}
     */
    public function store(StoreHotelConfigurationRequest $request): JsonResponse
    {
        $requestId = uniqid('req_', true);
        $validated = $request->validated();

        Log::info('Creando configuración de hotel', [
            'request_id' => $requestId,
            'data'       => $validated
        ]);

        $dto = new CreateHotelConfigurationDTO(
            hotelId: $validated['hotel_id'],
            roomTypeId: $validated['room_type_id'],
            accommodationId: $validated['accommodation_id'],
            quantity: $validated['quantity']
        );

        $configuration = $this->createHotelConfigurationUseCase->execute($dto);

        return response()->json([
            'id'               => $configuration->getId(),
            'hotel_id'         => $configuration->getHotelId(),
            'room_type_id'     => $configuration->getRoomTypeId(),
            'accommodation_id' => $configuration->getAccommodationId(),
            'quantity'         => $configuration->getQuantity(),
        ], 201);
    }

    /**
     * Update hotel configuration
     *
     * @urlParam id uuid required Hotel configuration ID.
     * @response 200 {
     *   "id": "a1b2c3d4-e5f6-7890-abcd-ef1234567890",
     *   "hotel_id": "f1e2d3c4-b5a6-7890-abcd-ef1234567890",
     *   "room_type_id": "b1c2d3e4-f5a6-7890-abcd-ef1234567890",
     *   "accommodation_id": "c1d2e3f4-a5b6-7890-abcd-ef1234567890",
     *   "quantity": 10
     * }
     * @response 404 {"message": "Hotel configuration not found"}
     * @response 422 {"message": "The given data was invalid."}
     */
    public function update(UpdateHotelConfigurationRequest $request, string $id): JsonResponse
    {
        $requestId = uniqid('req_', true);
        Log::info('Actualizando configuración de hotel', [
            'request_id'       => $requestId,
            'configuration_id' => $id,
            'data'             => $request->validated()
        ]);

        $data = $request->validated();
        $dto = new UpdateHotelConfigurationDTO(
            id: $id,
            hotelId: $data['hotel_id'],
            roomTypeId: $data['room_type_id'],
            accommodationId: $data['accommodation_id'],
            quantity: $data['quantity']
        );

        $configuration = $this->updateHotelConfigurationUseCase->execute($dto);

        Log::info('Configuración actualizada', [
            'request_id'       => $requestId,
            'configuration_id' => $configuration->getId()
        ]);

        return response()->json([
            'id'               => $configuration->getId(),
            'hotel_id'         => $configuration->getHotelId(),
            'room_type_id'     => $configuration->getRoomTypeId(),
            'accommodation_id' => $configuration->getAccommodationId(),
            'quantity'         => $configuration->getQuantity(),
        ]);
    }

    /**
     * Delete hotel configuration
     *
     * @urlParam id uuid required Hotel configuration ID.
     * @response 204 {}
     * @response 404 {"message": "Hotel configuration not found"}
     */
    public function destroy(string $id): JsonResponse
    {
        $requestId = uniqid('req_', true);

        Log::info('Eliminando configuración de hotel', [
            'request_id'       => $requestId,
            'configuration_id' => $id
        ]);

        $this->deleteHotelConfigurationUseCase->execute($id);

        Log::info('Configuración eliminada', [
            'request_id'       => $requestId,
            'configuration_id' => $id
        ]);

        return response()->json([], 204);
    }
}
