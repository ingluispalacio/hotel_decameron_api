<?php

namespace App\Modules\Hotel\Presentation\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use App\Modules\Hotel\Application\UseCases\Accommodation\GetAllAccommodationsUseCase;
use App\Modules\Hotel\Application\UseCases\Accommodation\GetAccommodationByIdUseCase;
use App\Modules\Hotel\Application\UseCases\Accommodation\GetAccommodationByNameUseCase;

/**
 * @authenticated
 * @group Accommodations
 *
 * APIs for managing accommodations.
 */
class AccommodationController extends Controller
{
    public function __construct(
        private readonly GetAllAccommodationsUseCase $getAllAccommodationsUseCase,
        private readonly GetAccommodationByIdUseCase $getAccommodationByIdUseCase,
        private readonly GetAccommodationByNameUseCase $getAccommodationByNameUseCase
    ) {}

    /**
     * List accommodations
     *
     * Returns all available accommodations.
     *
     * @response 200 [
     *   {
     *     "id": "c1b2d3e4-f5a6-7890-abcd-ef1234567890",
     *     "name": "SINGLE",
     *     "created_at": "2026-05-12T10:00:00.000000Z",
     *     "updated_at": "2026-05-12T10:00:00.000000Z"
     *   },
     *   {
     *     "id": "d2c3b4a5-f6e7-8901-abcd-ef9876543210",
     *     "name": "DOUBLE",
     *     "created_at": "2026-05-12T10:00:00.000000Z",
     *     "updated_at": "2026-05-12T10:00:00.000000Z"
     *   }
     * ]
     */
    public function index(): JsonResponse
    {
        $requestId = uniqid('req_', true);

        Log::info('Iniciando solicitud de obtención de acomodaciones', [
            'request_id' => $requestId,
            'function' => 'index',
            // 'user_id' => auth()->id()
        ]);

        $accommodations = $this->getAllAccommodationsUseCase->execute();

        Log::info('Acomodaciones obtenidas exitosamente', [
            'request_id' => $requestId,
            'function' => 'index',
            'count' => count($accommodations)
        ]);

        return response()->json($accommodations);
    }

    /**
     * Get accommodation by ID
     *
     * Returns a specific accommodation by its UUID.
     *
     * @urlParam id uuid required Accommodation ID.
     * Example: c1b2d3e4-f5a6-7890-abcd-ef1234567890
     *
     * @response 200 {
     *   "id": "c1b2d3e4-f5a6-7890-abcd-ef1234567890",
     *   "name": "SINGLE",
     *   "created_at": "2026-05-12T10:00:00.000000Z",
     *   "updated_at": "2026-05-12T10:00:00.000000Z"
     * }
     *
     * @response 404 {
     *   "message": "Accommodation not found."
     * }
     */
    public function show(string $id): JsonResponse
    {
        $requestId = uniqid('req_', true);

        Log::info('Iniciando solicitud de obtención de acomodación por ID', [
            'request_id' => $requestId,
            'function' => 'show',
            'accommodation_id' => $id
        ]);

        $accommodation = $this->getAccommodationByIdUseCase->execute($id);

        if (!$accommodation) {
            Log::warning('Acomodación no encontrada', [
                'request_id' => $requestId,
                'function' => 'show',
                'accommodation_id' => $id
            ]);

            return response()->json([
                'message' => 'Accommodation not found.'
            ], 404);
        }

        Log::info('Acomodación obtenida exitosamente', [
            'request_id' => $requestId,
            'function' => 'show',
            'accommodation_id' => $accommodation->getId()
        ]);

        return response()->json($accommodation->toArray());
    }

    /**
     * Find accommodation by name
     *
     * Returns an accommodation by its name.
     *
     * @urlParam name string required Accommodation name.
     * Example: SINGLE
     *
     * @response 200 {
     *   "id": "c1b2d3e4-f5a6-7890-abcd-ef1234567890",
     *   "name": "SINGLE",
     *   "created_at": "2026-05-12T10:00:00.000000Z",
     *   "updated_at": "2026-05-12T10:00:00.000000Z"
     * }
     *
     * @response 404 {
     *   "message": "Accommodation not found."
     * }
     */
    public function findByName(string $name): JsonResponse
    {
        $requestId = uniqid('req_', true);

        Log::info('Iniciando solicitud de búsqueda de acomodación por nombre', [
            'request_id' => $requestId,
            'function' => 'findByName',
            'name' => $name
        ]);

        $accommodation = $this->getAccommodationByNameUseCase->execute($name);

        if (!$accommodation) {
            Log::warning('Acomodación no encontrada por nombre', [
                'request_id' => $requestId,
                'function' => 'findByName',
                'name' => $name
            ]);

            return response()->json([
                'message' => 'Accommodation not found.'
            ], 404);
        }

        Log::info('Acomodación encontrada por nombre', [
            'request_id' => $requestId,
            'function' => 'findByName',
            'accommodation_id' => $accommodation->getId(),
            'name' => $accommodation->getName()
        ]);

        return response()->json($accommodation->toArray());
    }
}