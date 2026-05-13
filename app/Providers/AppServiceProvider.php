<?php

namespace App\Providers;

use App\Modules\Auth\Domain\Repositories\RoleRepositoryInterface;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Infrastructure\Repositories\RoleRepository;
use App\Modules\Auth\Infrastructure\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Modules\Hotel\Domain\Repositories\AccommodationRepositoryInterface;
use App\Modules\Hotel\Domain\Repositories\CityRepositoryInterface;
use App\Modules\Hotel\Domain\Repositories\HotelRepositoryInterface;
use App\Modules\Hotel\Domain\Repositories\HotelConfigurationRepositoryInterface;
use App\Modules\Hotel\Domain\Repositories\RoomTypeRepositoryInterface;
use App\Modules\Hotel\Infrastructure\Repositories\AccommodationRepository;
use App\Modules\Hotel\Infrastructure\Repositories\CityRepository;
use App\Modules\Hotel\Infrastructure\Repositories\HotelRepository;
use App\Modules\Hotel\Infrastructure\Repositories\HotelConfigurationRepository;
use App\Modules\Hotel\Infrastructure\Repositories\RoomTypeRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AccommodationRepositoryInterface::class,
            AccommodationRepository::class
        );

        $this->app->bind(
            CityRepositoryInterface::class,
            CityRepository::class
        );

        $this->app->bind(
            HotelRepositoryInterface::class,
            HotelRepository::class
        );

        $this->app->bind(
            HotelConfigurationRepositoryInterface::class,
            HotelConfigurationRepository::class
        );

        $this->app->bind(
            RoomTypeRepositoryInterface::class,
            RoomTypeRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class, 
            UserRepository::class
        );
        
        $this->app->bind(
            RoleRepositoryInterface::class, 
            RoleRepository::class
        );
    }
}
