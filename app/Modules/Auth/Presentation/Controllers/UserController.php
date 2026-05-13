<?php

namespace App\Modules\Auth\Presentation\Controllers;

use App\Modules\Auth\Application\DTOs\User\CreateUserDTO;
use App\Modules\Auth\Application\DTOs\User\UpdateUserDTO;
use App\Modules\Auth\Application\UseCases\User\CreateUserUseCase;
use App\Modules\Auth\Application\UseCases\User\DeleteUserUseCase;
use App\Modules\Auth\Application\UseCases\User\GetUserByIdUseCase;
use App\Modules\Auth\Application\UseCases\User\ListUsersUseCase;
use App\Modules\Auth\Application\UseCases\User\UpdateUserUseCase;
use App\Modules\Auth\Presentation\Requests\CreateUserRequest;
use App\Modules\Auth\Presentation\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Throwable;

/**
 * @authenticated
 * @group Users
 *
 * APIs for managing users.
 */
class UserController extends Controller
{
    public function __construct(
        private readonly ListUsersUseCase $listUsersUseCase,
        private readonly GetUserByIdUseCase $getUserByIdUseCase,
        private readonly CreateUserUseCase $createUserUseCase,
        private readonly UpdateUserUseCase $updateUserUseCase,
        private readonly DeleteUserUseCase $deleteUserUseCase,
    ) {}

    /**
     * List all users.
     *
     * @response 200 [
     *   {
     *     "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
     *     "first_name": "John",
     *     "last_name": "Doe",
     *     "email": "john@example.com",
     *     "address": "Main Street 123",
     *     "role_id": "123e4567-e89b-12d3-a456-426614174000",
     *     "status": "active",
     *     "created_at": "2025-01-01T00:00:00+00:00",
     *     "updated_at": "2025-01-01T00:00:00+00:00"
     *   }
     * ]
     */
    public function index(): JsonResponse
    {
        $users = $this->listUsersUseCase->execute();

        $data = array_map(fn($user) => [
            'id'         => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
            'email'      => $user->getEmail(),
            'address'    => $user->getAddress(),
            'role_id'    => $user->getRoleId(),
            'status'     => $user->getStatus(),
            'created_at' => $user->getCreatedAt()?->format('c'),
            'updated_at' => $user->getUpdatedAt()?->format('c'),
        ], $users);

        return response()->json($data);
    }

    /**
     * Get a single user by ID.
     *
     * @urlParam id string required User UUID. Example: 9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d
     *
     * @response 200 {
     *   "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
     *   "first_name": "John",
     *   "last_name": "Doe",
     *   "email": "john@example.com",
     *   "address": "Main Street 123",
     *   "role_id": "123e4567-e89b-12d3-a456-426614174000",
     *   "status": "active",
     *   "created_at": "2025-01-01T00:00:00+00:00",
     *   "updated_at": "2025-01-01T00:00:00+00:00"
     * }
     * @response 404 {"message": "Usuario no encontrado."}
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = $this->getUserByIdUseCase->execute($id);
            return response()->json([
                'id'         => $user->getId(),
                'first_name' => $user->getFirstName(),
                'last_name'  => $user->getLastName(),
                'email'      => $user->getEmail(),
                'address'    => $user->getAddress(),
                'role_id'    => $user->getRoleId(),
                'status'     => $user->getStatus(),
                'created_at' => $user->getCreatedAt()?->format('c'),
                'updated_at' => $user->getUpdatedAt()?->format('c'),
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Create a new user.
     *
     * @bodyParam first_name string required User's first name. Example: John
     * @bodyParam last_name string required User's last name. Example: Doe
     * @bodyParam birth_date date required User's birth date (Y-m-d). Example: 1990-01-01
     * @bodyParam email string required User's email. Example: john@example.com
     * @bodyParam password string required User's password (min 8). Example: secret123
     * @bodyParam address string required User's address. Example: Main Street 123
     * @bodyParam role_id string required Role UUID. Example: 123e4567-e89b-12d3-a456-426614174000
     * @bodyParam status string required User status (active/inactive). Example: active
     *
     * @response 201 {
     *   "message": "User created successfully",
     *   "user": {
     *     "first_name": "John",
     *     "last_name": "Doe",
     *     "email": "john@example.com",
     *     "address": "Main Street 123",
     *     "role_id": "123e4567-e89b-12d3-a456-426614174000",
     *     "status": "active"
     *   }
     * }
     * @response 422 {"message": "The given data was invalid.", "errors": {...}}
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $dto = new CreateUserDTO(
            firstName: $request->first_name,
            lastName: $request->last_name,
            birthDate: $request->birth_date,
            email: $request->email,
            password: $request->password,
            address: $request->address,
            roleId: $request->role_id,
            status: $request->status,
        );

        $this->createUserUseCase->execute($dto);

        return response()->json([
            'message' => 'User created successfully',
            'user'    => [
                'first_name' => $dto->firstName,
                'last_name'  => $dto->lastName,
                'email'      => $dto->email,
                'address'    => $dto->address,
                'role_id'    => $dto->roleId,
                'status'     => $dto->status,
            ],
        ], 201);
    }

    /**
     * Update an existing user.
     *
     * @urlParam id string required User UUID. Example: 9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d
     * @bodyParam first_name string optional New first name. Example: Jane
     * @bodyParam last_name string optional New last name. Example: Smith
     * @bodyParam birth_date date optional New birth date. Example: 1992-05-15
     * @bodyParam email string optional New email. Example: jane@example.com
     * @bodyParam address string optional New address. Example: Oak Avenue 456
     * @bodyParam role_id string optional New role UUID.
     * @bodyParam status string optional New status (active/inactive).
     *
     * @response 200 {
     *   "message": "User updated successfully",
     *   "user": {
     *     "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
     *     "first_name": "Jane",
     *     "last_name": "Smith",
     *     "email": "jane@example.com"
     *   }
     * }
     * @response 404 {"message": "Usuario no encontrado."}
     * @response 422 {"message": "The given data was invalid."}
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        try {
            $dto = new UpdateUserDTO(
                id: $id,
                firstName: $request->first_name ?? null,
                lastName: $request->last_name ?? null,
                birthDate: $request->birth_date ?? null,
                email: $request->email ?? null,
                address: $request->address ?? null,
                roleId: $request->role_id ?? null,
                status: $request->status ?? null,
            );
            $this->updateUserUseCase->execute($dto);

            return response()->json([
                'message' => 'User updated successfully',
                'user'    => array_filter([
                    'id'         => $id,
                    'first_name' => $dto->firstName,
                    'last_name'  => $dto->lastName,
                    'email'      => $dto->email,
                    'address'    => $dto->address,
                    'role_id'    => $dto->roleId,
                    'status'     => $dto->status,
                ]),
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Delete a user.
     *
     * @urlParam id string required User UUID. Example: 9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d
     *
     * @response 200 {"message": "User deleted"}
     * @response 404 {"message": "Usuario no encontrado."}
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->deleteUserUseCase->execute($id);
            return response()->json(['message' => 'User deleted']);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}