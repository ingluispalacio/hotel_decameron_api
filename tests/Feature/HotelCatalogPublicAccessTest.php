<?php

namespace Tests\Feature;

use Tests\TestCase;

class HotelCatalogPublicAccessTest extends TestCase
{
    public function test_cities_endpoint_is_accessible_without_authentication(): void
    {
        $prefix = config('api.prefix');

        $response = $this->get("/{$prefix}/cities");

        $response->assertStatus(200);
    }

    public function test_room_types_endpoint_is_accessible_without_authentication(): void
    {
        $prefix = config('api.prefix');

        $response = $this->get("/{$prefix}/room-types");

        $response->assertStatus(200);
    }

    public function test_accommodations_endpoint_is_accessible_without_authentication(): void
    {
        $prefix = config('api.prefix');

        $response = $this->get("/{$prefix}/accommodations");

        $response->assertStatus(200);
    }
}
