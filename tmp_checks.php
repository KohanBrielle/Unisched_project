<?php
require __DIR__ .  /vendor/autoload.php;
 = require __DIR__ . /bootstrap/app.php;
 = ->make(Illuminate\Contracts\Console\Kernel::class);
->bootstrap();
 = now();
 = App\Models\Reservation::where(status,approved)->where(start_time,<=,)->where(end_time,>,)->get();
echo Active approved reservations:  . ->count() .  \n;
foreach ( as ) {
    echo Reservation  . ->id .  facility  . ->facility_id .   . ->start_time .  to  . ->end_time .  \n;
}
