<?php

namespace App\Console\Commands;

use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:update-facility-status')]
#[Description('Update facility status based on current reservations and time')]
class UpdateFacilityStatus extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating facility statuses...');

        $facilities = Facility::all();
        $updatedCount = 0;

        foreach ($facilities as $facility) {
            $currentTime = now();

            if ($facility->status_overridden) {
                continue;
            }

            $hasActiveApprovedReservation = $facility->reservations()
                ->where('status', 'approved')
                ->where('start_time', '<=', $currentTime)
                ->where('end_time', '>', $currentTime)
                ->exists();

            $newStatus = 'open';

            if ($facility->status === 'closed') {
                $newStatus = 'closed';
            } elseif ($hasActiveApprovedReservation) {
                $newStatus = 'reserved';
            }

            if ($facility->status !== $newStatus) {
                $facility->update(['status' => $newStatus]);
                $updatedCount++;
                $this->info("Updated {$facility->room_name} from {$facility->status} to {$newStatus}");
            }
        }

        // Clean up expired reservations
        $expiredReservations = Reservation::where('status', 'approved')
            ->where('end_time', '<', now())
            ->get();

        foreach ($expiredReservations as $reservation) {
            $reservation->delete();
            $this->info("Deleted expired reservation for {$reservation->facility->room_name}");
        }

        $this->info("Facility status update completed. Updated {$updatedCount} facilities and cleaned up {$expiredReservations->count()} expired reservations.");
    }
}
