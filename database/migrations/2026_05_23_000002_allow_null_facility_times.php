<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->time('opening_time')->nullable()->default(null)->change();
            $table->time('closing_time')->nullable()->default(null)->change();
            $table->time('lunch_start')->nullable()->default(null)->change();
            $table->time('lunch_end')->nullable()->default(null)->change();
        });

        DB::table('facilities')
            ->whereNull('opening_time')
            ->update(['opening_time' => null]);
        DB::table('facilities')
            ->whereNull('closing_time')
            ->update(['closing_time' => null]);
        DB::table('facilities')
            ->whereNull('lunch_start')
            ->update(['lunch_start' => null]);
        DB::table('facilities')
            ->whereNull('lunch_end')
            ->update(['lunch_end' => null]);
    }

    public function down(): void
    {
        DB::table('facilities')
            ->whereNull('opening_time')
            ->update(['opening_time' => '07:00:00']);
        DB::table('facilities')
            ->whereNull('closing_time')
            ->update(['closing_time' => '17:00:00']);
        DB::table('facilities')
            ->whereNull('lunch_start')
            ->update(['lunch_start' => '12:00:00']);
        DB::table('facilities')
            ->whereNull('lunch_end')
            ->update(['lunch_end' => '13:00:00']);

        Schema::table('facilities', function (Blueprint $table) {
            $table->time('opening_time')->default('07:00:00')->change();
            $table->time('closing_time')->default('17:00:00')->change();
            $table->time('lunch_start')->default('12:00:00')->change();
            $table->time('lunch_end')->default('13:00:00')->change();
        });
    }
};
