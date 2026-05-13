<?php

namespace Tests\Feature;

use App\Modules\Auth\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase; // Importante para limpiar la BD de los tests
use Tests\TestCase;

class HotelCatalogPublicAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_cities_endpoint_authenticated_access(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->getJson("/api/v1/cities");

        $response->assertStatus(200);
    }

    public function test_room_types_endpoint_authenticated_access(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->getJson("/api/v1/room-types");

        $response->assertStatus(200);
    }

    public function test_accommodations_endpoint_authenticated_access(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->getJson("/api/v1/accommodations");

        $response->assertStatus(200);
    }

    // TEST ADICIONAL: Verificar que efectivamente REBOTA si no hay token
    public function test_cities_endpoint_denies_guest_access(): void
    {
        $response = $this->getJson("/api/v1/cities");

        $response->assertStatus(401); // Esperamos que falle si no hay login
    }
}