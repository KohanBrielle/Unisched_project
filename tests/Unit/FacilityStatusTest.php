<?php

use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('it reports open status during operating hours when there is no reservation', function () {
    Carbon::setTestNow(Carbon::parse('2026-05-23 08:00:00'));

    $facility = Facility::create([
        'room_name' => 'Library',
        'building' => 'Main Building',
        'capacity' => 50,
        'is_borrowable' => false,
        'current_occupancy' => 0,
        'status' => 'open',
        'status_overridden' => false,
        'opening_time' => '07:00:00',
        'closing_time' => '17:00:00',
        'lunch_start' => '12:00:00',
        'lunch_end' => '13:00:00',
        'lunch_mode' => 'scheduled',
        'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
    ]);

    expect($facility->computed_status)->toBe('open');
});

test('it reports lunch break status during the configured lunch window', function () {
    Carbon::setTestNow(Carbon::parse('2026-05-23 12:30:00'));

    $facility = Facility::create([
        'room_name' => 'Library',
        'building' => 'Main Building',
        'capacity' => 50,
        'is_borrowable' => false,
        'current_occupancy' => 0,
        'status' => 'open',
        'status_overridden' => false,
        'opening_time' => '07:00:00',
        'closing_time' => '17:00:00',
        'lunch_start' => '12:00:00',
        'lunch_end' => '13:00:00',
        'lunch_mode' => 'scheduled',
        'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
    ]);

    expect($facility->computed_status)->toBe('lunch_break');
});

test('it reports reserved status when a future reservation exists', function () {
    Carbon::setTestNow(Carbon::parse('2026-05-23 08:00:00'));

    $facility = Facility::create([
        'room_name' => 'Library',
        'building' => 'Main Building',
        'capacity' => 50,
        'is_borrowable' => false,
        'current_occupancy' => 0,
        'status' => 'open',
        'status_overridden' => false,
        'opening_time' => '07:00:00',
        'closing_time' => '17:00:00',
        'lunch_start' => '12:00:00',
        'lunch_end' => '13:00:00',
        'lunch_mode' => 'scheduled',
        'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
    ]);

    Reservation::create([
        'facility_id' => $facility->id,
        'user_id' => null,
        'start_time' => Carbon::parse('2026-05-23 14:00:00'),
        'end_time' => Carbon::parse('2026-05-23 15:00:00'),
        'status' => 'approved',
    ]);

    expect($facility->computed_status)->toBe('reserved');
});

test('it reports in_use status when an approved reservation is currently active', function () {
    Carbon::setTestNow(Carbon::parse('2026-05-23 10:00:00'));

    $facility = Facility::create([
        'room_name' => 'Library',
        'building' => 'Main Building',
        'capacity' => 50,
        'is_borrowable' => false,
        'current_occupancy' => 0,
        'status' => 'open',
        'status_overridden' => false,
        'opening_time' => '07:00:00',
        'closing_time' => '17:00:00',
        'lunch_start' => '12:00:00',
        'lunch_end' => '13:00:00',
        'lunch_mode' => 'scheduled',
        'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
    ]);

    Reservation::create([
        'facility_id' => $facility->id,
        'user_id' => null,
        'start_time' => Carbon::parse('2026-05-23 09:30:00'),
        'end_time' => Carbon::parse('2026-05-23 11:00:00'),
        'status' => 'approved',
    ]);

    expect($facility->computed_status)->toBe('in_use');
});

test('it honors manual overrides instead of recalculating schedule status', function () {
    Carbon::setTestNow(Carbon::parse('2026-05-23 03:00:00'));

    $facility = Facility::create([
        'room_name' => 'Library',
        'building' => 'Main Building',
        'capacity' => 50,
        'is_borrowable' => false,
        'current_occupancy' => 0,
        'status' => 'closed',
        'status_overridden' => true,
        'opening_time' => '07:00:00',
        'closing_time' => '17:00:00',
        'lunch_start' => '12:00:00',
        'lunch_end' => '13:00:00',
        'lunch_mode' => 'scheduled',
        'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
    ]);

    expect($facility->computed_status)->toBe('closed');
});

afterEach(function () {
    Carbon::setTestNow();
});
