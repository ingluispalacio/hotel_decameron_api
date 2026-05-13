<?php

namespace App\Modules\Auth\Presentation\Controllers;

use App\Modules\Auth\Application\DTOs\Role\CreateRoleDTO;
use App\Modules\Auth\Application\DTOs\Role\UpdateRoleDTO;
use App\Modules\Auth\Application\UseCases\Role\CreateRoleUseCase;
use App\Modules\Auth\Application\UseCases\Role\DeleteRoleUseCase;
use App\Modules\Auth\Application\UseCases\Role\GetRoleByIdUseCase;
use App\Modules\Auth\Application\UseCases\Role\ListRolesUseCase;
use App\Modules\Auth\Application\UseCases\Role\UpdateRoleUseCase;
use App\Modules\Auth\Domain\Enums\UserRoleEnum;
use App\Modules\Auth\Presentation\Requests\CreateRoleRequest;
use App\Modules\Auth\Presentation\Requests\UpdateRoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Throwable;

/**
 * @authenticated
 * @group Roles
 *
 * APIs for managing user roles.
 */
class RoleController extends Controller
{
    public function __construct(
        private readonly ListRolesUseCase $listRolesUseCase,
        private readonly GetRoleByIdUseCase $getRoleByIdUseCase,
        private readonly CreateRoleUseCase $createRoleUseCase,
        private readonly UpdateRoleUseCase $updateRoleUseCase,
        private readonly DeleteRoleUseCase $deleteRoleUseCase,
    ) {}

    /**
     * List all roles.
     *
     * @response 200 [
     *   {
     *     "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
     *     "title": "ADMIN",
     *     "description": "Administrator",
     *     "created_at": "2025-01-01T00:00:00.000000Z",
     *     "updated_at": "2025-01-01T00:00:00.000000Z"
     *   }
     * ]
     */
    public function index(): JsonResponse
    {
        $roles = $this->listRolesUseCase->execute();

        $data = array_map(fn($role) => [
            'id'          => $role->getId(),
            'title'       => $role->getTitle(),
            'description' => $role->getDescription(),
            'created_at'  => $role->getCreatedAt()?->format('c'),
            'updated_at'  => $role->getUpdatedAt()?->format('c'),
        ], $roles);

        return response()->json($data);
    }

    /**
     * Get a single role by ID.
     *
     * @urlParam id string required Role UUID. Example: 9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d
     *
     * @response 200 {
     *   "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
     *   "title": "ADMIN",
     *   "description": "Administrator",
     *   "created_at": "2025-01-01T00:00:00.000000Z",
     *   "updated_at": "2025-01-01T00:00:00.000000Z"
     * }
     * @response 404 {"message": "Rol no encontrado."}
     */
    public function show(string $id): JsonResponse
    {
        try {
            $role = $this->getRoleByIdUseCase->execute($id);
            return response()->json([
                'id'          => $role->getId(),
                'title'       => $role->getTitle(),
                'description' => $role->getDescription(),
                'created_at'  => $role->getCreatedAt()?->format('c'),
                'updated_at'  => $role->getUpdatedAt()?->format('c'),
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Create a new role.
     *
     * @bodyParam title string required Role title. Example: ADMIN
     * @bodyParam description string optional Role description. Example: Administrator
     *
     * @response 201 {
     *   "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
     *   "title": "ADMIN",
     *   "description": "Administrator"
     * }
     * @response 422 {"message": "The given data was invalid.", "errors": {...}}
     */
    public function store(CreateRoleRequest $request): JsonResponse
    {
        try {
            // Convertir string a enum
            $titleEnum = UserRoleEnum::from($request->title);
        } catch (\ValueError $e) {
            return response()->json(['message' => 'Invalid role title'], 422);
        }

        $dto = new CreateRoleDTO(
            title: $titleEnum,
            description: $request->description,
        );

        $this->createRoleUseCase->execute($dto);

        return response()->json([
            'message'     => 'Role created successfully',
            'title'       => $titleEnum->value,
            'description' => $dto->description,
        ], 201);
    }

    /**
     * Update an existing role.
     *
     * @urlParam id string required Role UUID. Example: 9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d
     * @bodyParam title string required Role title. Example: EDITOR
     * @bodyParam description string optional Role description. Example: Editor
     *
     * @response 200 {
     *   "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
     *   "title": "EDITOR",
     *   "description": "Editor"
     * }
     * @response 404 {"message": "Rol no encontrado."}
     * @response 422 {"message": "The given data was invalid."}
     */
    public function update(UpdateRoleRequest $request, string $id): JsonResponse
    {
        try {
            $dto = new UpdateRoleDTO(
                id: $id,
                title: $request->title,
                description: $request->description,
            );
            $this->updateRoleUseCase->execute($dto);

            return response()->json([
                'id'          => $id,
                'title'       => $dto->title,
                'description' => $dto->description,
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Delete a role.
     *
     * @urlParam id string required Role UUID. Example: 9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d
     *
     * @response 200 {"message": "Role deleted"}
     * @response 404 {"message": "Rol no encontrado."}
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->deleteRoleUseCase->execute($id);
            return response()->json(['message' => 'Role deleted']);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
