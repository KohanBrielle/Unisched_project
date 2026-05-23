<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (!Schema::hasColumn('facilities', 'lunch_mode')) {
                $table->string('lunch_mode')->default('scheduled')->after('lunch_end');
            }

            $table->json('operating_days')
                ->nullable()
                ->change();
        });

        DB::table('facilities')
            ->whereNull('lunch_mode')
            ->update(['lunch_mode' => 'scheduled']);

        DB::table('facilities')
            ->whereNull('operating_days')
            ->update([
                'operating_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (Schema::hasColumn('facilities', 'lunch_mode')) {
                $table->dropColumn('lunch_mode');
            }

            $table->json('operating_days')->nullable()->change();
        });
    }
};
