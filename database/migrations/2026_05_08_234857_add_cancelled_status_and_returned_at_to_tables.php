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
        // Add cancelled status to reservations enum (if using enum)
        // Note: Laravel doesn't enforce enum constraints by default, but we can add a check constraint

        // Add returned_at timestamp to borrowed_equipments table
        Schema::table('borrowed_equipments', function (Blueprint $table) {
            $table->timestamp('returned_at')->nullable()->after('return_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove returned_at column from borrowed_equipments table
        Schema::table('borrowed_equipments', function (Blueprint $table) {
            $table->dropColumn('returned_at');
        });
    }
};
