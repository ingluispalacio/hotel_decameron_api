<?php

namespace App\Modules\Auth\Presentation\Controllers;

use App\Modules\Auth\Application\DTOs\Auth\LoginDTO;
use App\Modules\Auth\Application\DTOs\Auth\RegisterUserDTO;
use App\Modules\Auth\Application\UseCases\Auth\LoginUseCase;
use App\Modules\Auth\Application\UseCases\Auth\RegisterUserUseCase;
use App\Modules\Auth\Application\UseCases\Auth\LogoutUseCase;
use App\Modules\Auth\Application\UseCases\Auth\GetAuthenticatedUserUseCase;
use App\Modules\Auth\Presentation\Requests\LoginRequest;
use App\Modules\Auth\Presentation\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Throwable;

/**
 * @group Authentication
 * 
 * APIs for user registration, login, logout, and profile.
 */
class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterUserUseCase $registerUseCase,
        private readonly LoginUseCase $loginUseCase,
        private readonly LogoutUseCase $logoutUseCase,
        private readonly GetAuthenticatedUserUseCase $getAuthenticatedUserUseCase,
    ) {}

    /**
     * Register a new user.
     *
     * @bodyParam first_name string required The user's first name. Example: John
     * @bodyParam last_name string required The user's last name. Example: Doe
     * @bodyParam birth_date date required The user's birth date (Y-m-d). Example: 1990-01-01
     * @bodyParam email string required The user's email address. Example: john@example.com
     * @bodyParam password string required The user's password (min 8 characters). Example: secret123
     * @bodyParam address string required The user's address. Example: Main Street 123
     * @bodyParam role_id string required Role UUID. Example: 123e4567-e89b-12d3-a456-426614174000
     *
     * @response 201 {
     *   "message": "User registered successfully",
     *   "user": {
     *       "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
     *       "first_name": "John",
     *       "last_name": "Doe",
     *       "email": "john@example.com",
     *       "address": "Main Street 123",
     *       "role_id": "123e4567-e89b-12d3-a456-426614174000",
     *       "status": "active"
     *   },
     *   "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
     * }
     * @response 422 {"message": "The given data was invalid.", "errors": {...}}
     * @response 400 {"message": "Rol no válido."}
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $dto = new RegisterUserDTO(
                firstName: $request->first_name,
                lastName: $request->last_name,
                birthDate: $request->birth_date,
                email: $request->email,
                password: $request->password,
                address: $request->address,
                roleId: $request->role_id,
            );

            $result = $this->registerUseCase->execute($dto);

            return response()->json([
                'message' => 'User registered successfully',
                'user'    => $result->user,
                'token'   => $result->token,
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Login a user.
     *
     * @bodyParam email string required The user's email. Example: john@example.com
     * @bodyParam password string required The user's password. Example: secret123
     *
     * @response 200 {
     *   "message": "Login successful",
     *   "user": {
     *       "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
     *       "first_name": "John",
     *       "last_name": "Doe",
     *       "email": "john@example.com",
     *       "address": "Main Street 123",
     *       "role_id": "123e4567-e89b-12d3-a456-426614174000",
     *       "status": "active"
     *   },
     *   "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
     * }
     * @response 401 {"message": "Credenciales incorrectas."}
     * @response 400 {"message": "Usuario inactivo o suspendido."}
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $dto = new LoginDTO(
                email: $request->email,
                password: $request->password
            );

            $result = $this->loginUseCase->execute($dto);

            return response()->json([
                'message' => 'Login successful',
                'user'    => $result->user,
                'token'   => $result->token,
            ]);
        } catch (Throwable $e) {
            $status = str_contains($e->getMessage(), 'Credenciales') ? 401 : 400;
            return response()->json(['message' => $e->getMessage()], $status);
        }
    }

    /**
     * Logout the authenticated user (invalidate JWT token).
     *
     * @authenticated
     * @response 200 {"message": "Logout successful"}
     */
    public function logout(): JsonResponse
    {
        try {
            $this->logoutUseCase->execute();
            return response()->json(['message' => 'Logout successful']);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Logout failed'], 500);
        }
    }

    /**
     * Get authenticated user profile.
     *
     * @authenticated
     * @response 200 {
     *   "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
     *   "first_name": "John",
     *   "last_name": "Doe",
     *   "email": "john@example.com",
     *   "address": "Main Street 123",
     *   "role_id": "123e4567-e89b-12d3-a456-426614174000",
     *   "status": "active"
     * }
     * @response 401 {"message": "No autenticado."}
     */
    public function me(): JsonResponse
    {
        try {
            $user = $this->getAuthenticatedUserUseCase->execute();
            return response()->json($user->toArray());
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}