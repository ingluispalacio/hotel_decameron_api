<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hotel_configurations', function (Blueprint $table) {
            $table->uuid('id')->primary();
             $table->foreignUuid('hotel_id')
                ->constrained('hotels')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignUuid('room_type_id')
                ->constrained('room_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignUuid('accommodation_id')
                ->constrained('accommodations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedInteger('quantity');
            $table->timestamps();
            $table->softDeletes();

            $table->unique([
                'hotel_id',
                'room_type_id',
                'accommodation_id'
            ], 'hotel_configuration_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_configurations');
    }
};
