<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            if (!Schema::hasColumn('locations', 'external_id')) {
                $table->string('external_id')->nullable()->index()->after('source_id');
            }
            if (!Schema::hasColumn('locations', 'website')) {
                $table->string('website')->nullable()->after('address');
            }
            if (!Schema::hasColumn('locations', 'phone')) {
                $table->string('phone')->nullable()->after('website');
            }
            if (!Schema::hasColumn('locations', 'opening_hours')) {
                $table->string('opening_hours')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('locations', 'accessibility_info')) {
                $table->text('accessibility_info')->nullable()->after('opening_hours');
            }
            if (!Schema::hasColumn('locations', 'rating')) {
                $table->decimal('rating', 3, 2)->nullable()->after('accessibility_info');
            }
            if (!Schema::hasColumn('locations', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('locations', 'last_seen_at')) {
                $table->timestamp('last_seen_at')->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('locations', 'last_synced_at')) {
                $table->timestamp('last_synced_at')->nullable()->after('last_seen_at');
            }
        });

        if (!Schema::hasTable('location_sync_logs')) {
            Schema::create('location_sync_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
                $table->string('provider')->default('openstreetmap');
                $table->integer('search_radius')->default(10000);
                $table->integer('discovered_count')->default(0);
                $table->integer('created_count')->default(0);
                $table->integer('updated_count')->default(0);
                $table->integer('duplicate_count')->default(0);
                $table->integer('skipped_count')->default(0);
                $table->integer('deactivated_count')->default(0);
                $table->string('status')->default('running'); // running, success, partial, failed
                $table->text('error_message')->nullable();
                $table->timestamp('started_at');
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('location_sync_logs');

        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn([
                'external_id',
                'website',
                'phone',
                'opening_hours',
                'accessibility_info',
                'rating',
                'is_verified',
                'last_seen_at',
                'last_synced_at',
            ]);
        });
    }
};
