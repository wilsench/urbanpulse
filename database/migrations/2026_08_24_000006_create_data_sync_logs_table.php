<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source')->index(); // bmkg, air_quality, openstreetmap
            $table->string('status'); // SUCCESS, FAILED, CACHED_FALLBACK
            $table->text('message')->nullable();
            $table->integer('records_synced')->default(0);
            $table->timestamp('synced_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_sync_logs');
    }
};
