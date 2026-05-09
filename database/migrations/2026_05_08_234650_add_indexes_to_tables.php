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
        // Add indexes to reservations table
        Schema::table('reservations', function (Blueprint $table) {
            $table->index(['facility_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('start_time');
            $table->index('end_time');
        });

        // Add indexes to attendance_logs table
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->index(['facility_id', 'time_in']);
            $table->index(['user_id', 'time_in']);
        });

        // Add indexes to borrowed_equipments table
        Schema::table('borrowed_equipments', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index(['equipment_name', 'status']);
            $table->index('return_date');
        });

        // Add indexes to facilities table
        Schema::table('facilities', function (Blueprint $table) {
            $table->index('status');
            $table->index('is_borrowable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes from reservations table
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex(['facility_id', 'status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['start_time']);
            $table->dropIndex(['end_time']);
        });

        // Drop indexes from attendance_logs table
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->dropIndex(['facility_id', 'time_in']);
            $table->dropIndex(['user_id', 'time_in']);
        });

        // Drop indexes from borrowed_equipments table
        Schema::table('borrowed_equipments', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['equipment_name', 'status']);
            $table->dropIndex(['return_date']);
        });

        // Drop indexes from facilities table
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['is_borrowable']);
        });
    }
};
