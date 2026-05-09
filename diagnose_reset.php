<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Current time: " . now() . "\n\n";

echo "Facilities:\n";
foreach (App\Models\Facility::all() as $facility) {
    echo "{$facility->id} {$facility->room_name} status={$facility->status} status_overridden=" . ($facility->status_overridden ? 'true' : 'false') . "\n";
}

echo "\nReservations:\n";
foreach (App\Models\Reservation::all() as $reservation) {
    echo "{$reservation->id} facility_id={$reservation->facility_id} status={$reservation->status} {$reservation->start_time} to {$reservation->end_time}\n";
}

echo "\nActive approved reservations now:\n";
$active = App\Models\Reservation::where('status','approved')->where('start_time','<=',now())->where('end_time','>',now())->get();
foreach ($active as $reservation) {
    echo "{$reservation->id} facility_id={$reservation->facility_id} {$reservation->start_time} to {$reservation->end_time}\n";
}

echo "\n";
?>