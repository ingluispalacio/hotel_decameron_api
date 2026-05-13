<?php

namespace App\Modules\Hotel\Presentation\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use App\Modules\Hotel\Presentation\Requests\Hotel\StoreHotelRequest;
use App\Modules\Hotel\Presentation\Requests\Hotel\UpdateHotelRequest;

use App\Modules\Hotel\Application\DTOs\Hotel\CreateHotelDTO;
use App\Modules\Hotel\Application\DTOs\Hotel\UpdateHotelDTO;
use App\Modules\Hotel\Application\DTOs\Hotel\HotelFiltersDTO;

use App\Modules\Hotel\Application\UseCases\Hotel\ListHotelsUseCase;
use App\Modules\Hotel\Application\UseCases\Hotel\GetHotelByIdUseCase;
use App\Modules\Hotel\Application\UseCases\Hotel\CreateHotelUseCase;
use App\Modules\Hotel\Application\UseCases\Hotel\UpdateHotelUseCase;
use App\Modules\Hotel\Application\UseCases\Hotel\DeleteHotelUseCase;

/**
 * @authenticated
 * @group Hotels
 *
 * APIs for managing hotels.
 */
class HotelController extends Controller
{
    public function __construct(
        private readonly ListHotelsUseCase $listHotelsUseCase,
        private readonly GetHotelByIdUseCase $getHotelByIdUseCase,
        private readonly CreateHotelUseCase $createHotelUseCase,
        private readonly UpdateHotelUseCase $updateHotelUseCase,
        private readonly DeleteHotelUseCase $deleteHotelUseCase
    ) {}

    /**
     * List hotels
     *
     * Returns a paginated list of hotels.
     *
     * @queryParam search string Optional search term. Example: Decameron
     * @queryParam per_page integer Number of results per page. Example: 10
     * @queryParam page integer Page number. Example: 1
     *
     * @response 200 {
     *   "current_page": 1,
     *   "data": [
     *     {
     *       "id": "b1c2d3e4-f5a6-7890-abcd-ef1234567890",
     *       "name": "Decameron Cartagena",
     *       "address": "Bocagrande Avenue",
     *       "city_id": "c1d2e3f4-a5b6-7890-abcd-ef1234567890",
     *       "nit": "900123456",
     *       "max_rooms": 150
     *     }
     *   ],
     *   "per_page": 10,
     *   "total": 1
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $filters = new HotelFiltersDTO(
            search: $request->query('search'),
            perPage: (int) $request->query('per_page', 10),
            page: (int) $request->query('page', 1)
        );

        $paginatedResult = $this->listHotelsUseCase->execute($filters);

        // Serializar el objeto PaginatedResult
        $data = array_map(fn($hotel) => [
            'id'         => $hotel->getId(),
            'name'       => $hotel->getName(),
            'address'    => $hotel->getAddress(),
            'city_id'    => $hotel->getCityId(),
            'nit'        => $hotel->getNit(),
            'max_rooms'  => $hotel->getMaxRooms(),
        ], $paginatedResult->items());

        return response()->json([
            'current_page' => $paginatedResult->currentPage(),
            'data'         => $data,
            'per_page'     => $paginatedResult->perPage(),
            'total'        => $paginatedResult->total(),
        ]);
    }

    /**
     * Get hotel by ID
     *
     * @urlParam id uuid required Hotel ID.
     * @response 200 {
     *   "id": "b1c2d3e4-f5a6-7890-abcd-ef1234567890",
     *   "name": "Decameron Cartagena",
     *   "address": "Bocagrande Avenue",
     *   "city_id": "c1d2e3f4-a5b6-7890-abcd-ef1234567890",
     *   "nit": "900123456",
     *   "max_rooms": 150
     * }
     * @response 404 {"message": "Hotel not found."}
     */
    public function show(string $id): JsonResponse
    {
        $hotel = $this->getHotelByIdUseCase->execute($id);

        return response()->json([
            'id'         => $hotel->getId(),
            'name'       => $hotel->getName(),
            'address'    => $hotel->getAddress(),
            'city_id'    => $hotel->getCityId(),
            'nit'        => $hotel->getNit(),
            'max_rooms'  => $hotel->getMaxRooms(),
        ]);
    }

    /**
     * Create hotel
     *
     * @response 201 {
     *   "id": "b1c2d3e4-f5a6-7890-abcd-ef1234567890",
     *   "name": "Decameron Cartagena",
     *   "address": "Bocagrande Avenue",
     *   "city_id": "c1d2e3f4-a5b6-7890-abcd-ef1234567890",
     *   "nit": "900123456",
     *   "max_rooms": 150
     * }
     * @response 422 {"message": "The given data was invalid."}
     */
    public function store(StoreHotelRequest $request): JsonResponse
    {
        $requestId = uniqid('req_', true);
        Log::info('Creando hotel', [
            'request_id' => $requestId,
            'ip'         => $request->ip(),
            'data'       => $request->validated()
        ]);

        $data = $request->validated();
        $dto = new CreateHotelDTO(
            name:     $data['name'],
            address:  $data['address'],
            cityId:   $data['city_id'],
            nit:      $data['nit'],
            maxRooms: $data['max_rooms']
        );

        $hotel = $this->createHotelUseCase->execute($dto);

        Log::info('Hotel creado', [
            'request_id' => $requestId,
            'hotel_id'   => $hotel->getId(),
            'hotel_name' => $hotel->getName()
        ]);

        return response()->json([
            'id'         => $hotel->getId(),
            'name'       => $hotel->getName(),
            'address'    => $hotel->getAddress(),
            'city_id'    => $hotel->getCityId(),
            'nit'        => $hotel->getNit(),
            'max_rooms'  => $hotel->getMaxRooms(),
        ], 201);
    }

    /**
     * Update hotel
     *
     * @urlParam id uuid required Hotel ID.
     * @response 200 {
     *   "id": "b1c2d3e4-f5a6-7890-abcd-ef1234567890",
     *   "name": "Updated Hotel",
     *   "address": "New Address",
     *   "city_id": "c1d2e3f4-a5b6-7890-abcd-ef1234567890",
     *   "nit": "900123456",
     *   "max_rooms": 200
     * }
     * @response 404 {"message": "Hotel not found."}
     * @response 422 {"message": "The given data was invalid."}
     */
    public function update(UpdateHotelRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $dto = new UpdateHotelDTO(
            id:       $id,
            name:     $data['name'],
            address:  $data['address'],
            cityId:   $data['city_id'],
            nit:      $data['nit'],
            maxRooms: $data['max_rooms']
        );

        $hotel = $this->updateHotelUseCase->execute($dto);

        return response()->json([
            'id'         => $hotel->getId(),
            'name'       => $hotel->getName(),
            'address'    => $hotel->getAddress(),
            'city_id'    => $hotel->getCityId(),
            'nit'        => $hotel->getNit(),
            'max_rooms'  => $hotel->getMaxRooms(),
        ]);
    }

    /**
     * Delete hotel
     *
     * @urlParam id uuid required Hotel ID.
     * @response 204 {}
     * @response 404 {"message": "Hotel not found."}
     */
    public function destroy(string $id): JsonResponse
    {
        $this->deleteHotelUseCase->execute($id);
        return response()->json([], 204);
    }
}