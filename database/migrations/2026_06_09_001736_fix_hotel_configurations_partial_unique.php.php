<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Eliminar la restricción (no el índice) creada por la migración uno
        DB::statement('ALTER TABLE hotel_configurations DROP CONSTRAINT IF EXISTS hotel_configuration_unique_active');
        
        // 2. Eliminar cualquier restricción única antigua que pudiera existir
        DB::statement('ALTER TABLE hotel_configurations DROP CONSTRAINT IF EXISTS hotel_configuration_unique');
        
        // 3. Eliminar índices sueltos con esos nombres (por si acaso)
        DB::statement('DROP INDEX IF EXISTS hotel_configuration_unique');
        DB::statement('DROP INDEX IF EXISTS hotel_configuration_unique_active');
        
        // 4. Crear el índice único parcial (esto SÍ es un índice, no una constraint)
        DB::statement('
            CREATE UNIQUE INDEX hotel_configuration_unique_active 
            ON hotel_configurations (hotel_id, room_type_id, accommodation_id) 
            WHERE deleted_at IS NULL
        ');
    }

    public function down(): void
    {
        // Revertir: eliminar el índice parcial
        DB::statement('DROP INDEX IF EXISTS hotel_configuration_unique_active');
        
        // Restaurar la restricción única normal (para todos los registros)
        DB::statement('
            ALTER TABLE hotel_configurations 
            ADD CONSTRAINT hotel_configuration_unique 
            UNIQUE (hotel_id, room_type_id, accommodation_id)
        ');
    }
};