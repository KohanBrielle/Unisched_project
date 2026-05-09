<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Facilities:\n";
foreach (App\Models\Facility::all() as $facility) {
    echo "{$facility->id} {$facility->room_name} status={$facility->status}\n";
}

echo "\nReservations:\n";
foreach (App\Models\Reservation::all() as $reservation) {
    echo "{$reservation->id} facility_id={$reservation->facility_id} status={$reservation->status} {$reservation->start_time} to {$reservation->end_time}\n";
}

echo "\nCurrent time: " . now() . "\n";
?>