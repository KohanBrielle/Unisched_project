<?php

namespace App\Console\Commands;

use App\Models\AttendanceLog;
use App\Models\Reservation;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:reset-attendance-log')]
#[Description('Reset attendance logs and clean up expired reservations at midnight')]
class ResetAttendanceLog extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting midnight reset: attendance logs and expired reservations cleanup...');

        // Reset attendance logs
        $deletedCount = AttendanceLog::truncate();
        $this->info('Attendance logs have been reset (all records cleared).');

        // Clean up expired reservations
        $expiredReservations = Reservation::where('status', 'approved')
            ->where('end_time', '<', now())
            ->get();

        $expiredCount = $expiredReservations->count();

        foreach ($expiredReservations as $reservation) {
            $reservation->delete();
            $this->info("Deleted expired reservation for {$reservation->facility->room_name}");
        }

        $this->info("Midnight reset completed successfully. Cleared attendance logs and cleaned up {$expiredCount} expired reservations.");
    }
}
