<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('environmental_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('city')->default('Bogor');
            $table->decimal('latitude', 10, 7)->index();
            $table->decimal('longitude', 10, 7)->index();
            $table->decimal('temperature', 5, 2)->nullable();
            $table->integer('humidity')->nullable();
            $table->integer('weather_code')->nullable();
            $table->string('weather_description')->nullable();
            $table->decimal('rainfall', 6, 2)->nullable();
            $table->integer('rain_probability')->nullable();
            $table->integer('air_quality_index')->nullable();
            $table->decimal('pm25', 6, 2)->nullable();
            $table->string('air_quality_status')->nullable();
            $table->timestamp('recorded_at')->index();
            $table->string('source')->index(); // bmkg, open-meteo, waqi, iqair, urbanpulse
            $table->boolean('is_cached')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('environmental_data');
    }
};
