<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE hotel_configurations DROP CONSTRAINT IF EXISTS hotel_configuration_unique_active');
            DB::statement('ALTER TABLE hotel_configurations DROP CONSTRAINT IF EXISTS hotel_configuration_unique');
        }

        DB::statement('DROP INDEX IF EXISTS hotel_configuration_unique');
        DB::statement('DROP INDEX IF EXISTS hotel_configuration_unique_active');

        DB::statement('
            CREATE UNIQUE INDEX hotel_configuration_unique_active
            ON hotel_configurations (
                hotel_id,
                room_type_id,
                accommodation_id
            )
            WHERE deleted_at IS NULL
        ');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS hotel_configuration_unique_active');

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('
                ALTER TABLE hotel_configurations
                ADD CONSTRAINT hotel_configuration_unique
                UNIQUE (
                    hotel_id,
                    room_type_id,
                    accommodation_id
                )
            ');
        }
    }
};