<?php

namespace App\Modules\Hotel\Presentation\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use App\Modules\Hotel\Presentation\Requests\City\StoreCityRequest;

use App\Modules\Hotel\Application\DTOs\City\CreateCityDTO;
use App\Modules\Hotel\Application\UseCases\City\ListCitiesUseCase;
use App\Modules\Hotel\Application\UseCases\City\CreateCityUseCase;

/**
 * @authenticated
 * @group Cities
 *
 * APIs for managing cities.
 */
class CityController extends Controller
{
    public function __construct(
        private readonly ListCitiesUseCase $listCitiesUseCase,
        private readonly CreateCityUseCase $createCityUseCase
    ) {}

    /**
     * List cities
     *
     * Returns all available cities.
     *
     * @response 200 [
     *   {
     *     "id": "b1c2d3e4-f5a6-7890-abcd-ef1234567890",
     *     "name": "Cartagena",
     *     "created_at": "2026-05-12T10:00:00.000000Z",
     *     "updated_at": "2026-05-12T10:00:00.000000Z"
     *   },
     *   {
     *     "id": "c2d3e4f5-a6b7-8901-abcd-ef9876543210",
     *     "name": "Santa Marta",
     *     "created_at": "2026-05-12T10:00:00.000000Z",
     *     "updated_at": "2026-05-12T10:00:00.000000Z"
     *   }
     * ]
     */
    public function index(): JsonResponse
    {
        $requestId = uniqid('req_', true);

        Log::info('Iniciando solicitud de obtención de ciudades', [
            'request_id' => $requestId,
            'function' => 'index',
            // 'user_id' => auth()->id()
        ]);

        $cities = $this->listCitiesUseCase->execute();

        Log::info('Ciudades obtenidas exitosamente', [
            'request_id' => $requestId,
            'function' => 'index',
            'count' => count($cities)
        ]);

        return response()->json($cities);
    }

    /**
     * Create city
     *
     * Creates a new city.
     *
     * @bodyParam name string required City name.
     * Example: Barranquilla
     *
     * @response 201 {
     *   "id": "d3e4f5a6-b7c8-9012-abcd-ef4567891234",
     *   "name": "Barranquilla",
     *   "created_at": "2026-05-12T10:00:00.000000Z",
     *   "updated_at": "2026-05-12T10:00:00.000000Z"
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "name": [
     *       "The name field is required."
     *     ]
     *   }
     * }
     */
    public function store(StoreCityRequest $request): JsonResponse
    {
        $requestId = uniqid('req_', true);

        Log::info('Iniciando solicitud de creación de ciudad', [
            'request_id' => $requestId,
            'function' => 'store',
            // 'user_id' => auth()->id(),
            'data' => $request->validated()
        ]);

        $dto = new CreateCityDTO(
            name: $request->validated()['name']
        );

        $city = $this->createCityUseCase->execute($dto);

        Log::info('Ciudad creada exitosamente', [
            'request_id' => $requestId,
            'function' => 'store',
            'city_id' => $city->getId(),
            'city_name' => $city->getName()
        ]);

        return response()->json($city, 201);
    }
}