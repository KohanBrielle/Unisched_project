<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (!Schema::hasColumn('facilities', 'opening_time')) {
                $table->time('opening_time')->default('07:00:00');
            }
            if (!Schema::hasColumn('facilities', 'closing_time')) {
                $table->time('closing_time')->default('17:00:00');
            }
            if (!Schema::hasColumn('facilities', 'lunch_start')) {
                $table->time('lunch_start')->default('12:00:00');
            }
            if (!Schema::hasColumn('facilities', 'lunch_end')) {
                $table->time('lunch_end')->default('13:00:00');
            }
            if (!Schema::hasColumn('facilities', 'current_status')) {
                $table->enum('current_status', ['open', 'closed', 'lunch_break', 'maintenance'])->default('open');
            }
            if (!Schema::hasColumn('facilities', 'manual_override')) {
                $table->boolean('manual_override')->default(false);
            }
            if (!Schema::hasColumn('facilities', 'operating_days')) {
                $table->json('operating_days');
            }
        });

        // Set default operating days after table creation
        DB::table('facilities')->update(['operating_days' => '["monday", "tuesday", "wednesday", "thursday", "friday"]']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['opening_time', 'closing_time', 'lunch_start', 'lunch_end', 'current_status', 'manual_override', 'operating_days']);
        });
    }
};
