<?php

use Illuminate\Support\Facades\Route;

// Controladores de Hoteles
use App\Modules\Hotel\Presentation\Controllers\HotelController;
use App\Modules\Hotel\Presentation\Controllers\CityController;
use App\Modules\Hotel\Presentation\Controllers\RoomTypeController;
use App\Modules\Hotel\Presentation\Controllers\AccommodationController;
use App\Modules\Hotel\Presentation\Controllers\HotelConfigurationController;

// Controladores de Auth
use App\Modules\Auth\Presentation\Controllers\AuthController;
use App\Modules\Auth\Presentation\Controllers\UserController;
use App\Modules\Auth\Presentation\Controllers\RoleController;

Route::prefix(config('api.prefix'))->group(function () {

    /*
    |--------------------------------------------------------------------------
    | RUTAS PÚBLICAS
    |--------------------------------------------------------------------------
    */
    
    // Auth - Acceso
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });

   
    /*
    |--------------------------------------------------------------------------
    | RUTAS PRIVADAS (Requieren JWT - Middleware auth:api)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:api')->group(function () {

        // Sesión
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('auth/me', [AuthController::class, 'me']);

        // Gestión de Hoteles
        Route::prefix('hotels')->group(function () {
            Route::post('/', [HotelController::class, 'store']);
            Route::put('{id}', [HotelController::class, 'update']);
            Route::patch('{id}', [HotelController::class, 'update']);
            Route::delete('{id}', [HotelController::class, 'destroy']);
        });

        // Configuración y Ciudades
        Route::post('cities', [CityController::class, 'store']);
        
        Route::prefix('hotel-configurations')->group(function () {
            Route::post('/', [HotelConfigurationController::class, 'store']);
            Route::put('{id}', [HotelConfigurationController::class, 'update']);
            Route::patch('{id}', [HotelConfigurationController::class, 'update']);
            Route::delete('{id}', [HotelConfigurationController::class, 'destroy']);
        });

         // Hoteles - Consulta
    Route::get('hotels', [HotelController::class, 'index']);
    Route::get('hotels/{id}', [HotelController::class, 'show']);
    Route::get('cities', [CityController::class, 'index']);
    Route::get('room-types', [RoomTypeController::class, 'index']);
    Route::get('room-types/{id}', [RoomTypeController::class, 'show']);
    Route::get('accommodations', [AccommodationController::class, 'index']);
    Route::get('accommodations/{id}', [AccommodationController::class, 'show']);
    Route::get('accommodations/name/{name}', [AccommodationController::class, 'findByName']);


        /*
        |--------------------------------------------------------------------------
        | Gestión de Usuarios y Roles
        |--------------------------------------------------------------------------
        */
        
        // Usuarios
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('{id}', [UserController::class, 'show']);
            Route::put('{id}', [UserController::class, 'update']);
            Route::patch('{id}', [UserController::class, 'update']);
            Route::delete('{id}', [UserController::class, 'destroy']);
        });

        // Roles
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::post('/', [RoleController::class, 'store']);
            Route::get('{id}', [RoleController::class, 'show']);
            Route::put('{id}', [RoleController::class, 'update']);
            Route::patch('{id}', [RoleController::class, 'update']);
            Route::delete('{id}', [RoleController::class, 'destroy']);
        });
    });
});