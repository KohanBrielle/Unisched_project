<?php

namespace Tests\Feature;

use App\Models\AssistanceRequest;
use App\Models\AttendanceLog;
use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AdminFacilityFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_admin_can_create_a_borrowable_facility_and_it_is_visible_on_the_admin_page(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['_token' => 'test-token'])
            ->withHeaders([
                'X-CSRF-TOKEN' => 'test-token',
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->postJson('/admin/facilities', [
                'room_name' => 'ENB Room 301',
                'building' => 'ENB Building',
                'capacity' => 30,
                'is_borrowable' => true,
                'current_occupancy' => 0,
                'opening_time' => '07:30',
                'lunch_start' => '12:00',
                'lunch_end' => '13:00',
                'closing_time' => '17:00',
            ]);

        $response->assertSuccessful()
            ->assertJsonFragment([
                'room_name' => 'ENB Room 301',
                'building' => 'ENB Building',
                'is_borrowable' => true,
            ]);

        $this->assertDatabaseHas('facilities', [
            'room_name' => 'ENB Room 301',
            'building' => 'ENB Building',
            'is_borrowable' => true,
            'opening_time' => '07:30:00',
            'lunch_start' => '12:00:00',
            'lunch_end' => '13:00:00',
            'closing_time' => '17:00:00',
        ]);

        $adminPage = $this->actingAs($admin)->get('/system-admin');
        $adminPage->assertStatus(200)
            ->assertSee('Facility Management')
            ->assertSee('ENB Room 301');
    }

    public function test_students_can_submit_an_assistance_request_for_a_closed_facility(): void
    {
        $student = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $facility = Facility::create([
            'room_name' => 'Library',
            'building' => 'Academic Building',
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

        $response = $this->actingAs($student)
            ->withSession(['_token' => 'test-token'])
            ->withHeaders([
                'X-CSRF-TOKEN' => 'test-token',
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->postJson('/facility/assistance', [
                'facility_id' => $facility->id,
                'message' => 'I need the key for the library because the doors are locked.',
            ]);

        $response->assertSuccessful()
            ->assertJsonFragment([
                'facility_id' => $facility->id,
                'status' => 'pending',
            ]);

        $this->assertDatabaseHas('assistance_requests', [
            'user_id' => $student->id,
            'facility_id' => $facility->id,
            'status' => 'pending',
        ]);
        $this->assertEquals('I need the key for the library because the doors are locked.',
            AssistanceRequest::first()->message);
    }

    public function test_status_api_returns_schedule_based_messages_for_a_closed_or_lunch_state(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-23 12:30:00'));

        $student = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $facility = Facility::create([
            'room_name' => 'Gym',
            'building' => 'Sports Complex',
            'capacity' => 100,
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

        $response = $this->actingAs($student)->getJson('/api/facilities/status');

        $response->assertSuccessful();
        $payload = $response->json();

        $facilityState = collect($payload['facilities'])->firstWhere('id', $facility->id);

        $this->assertNotNull($facilityState);
        $this->assertSame('lunch_break', $facilityState['computed_status']);
        $this->assertTrue($facilityState['assistance_required']);
        $this->assertStringContainsString('lunch', strtolower($facilityState['status_message']));

        Carbon::setTestNow();
    }

    public function test_closed_facilities_reject_scan_requests_and_do_not_create_attendance_logs(): void
    {
        $student = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $facility = Facility::create([
            'room_name' => 'Closed Access Lab',
            'building' => 'Engineering Wing',
            'capacity' => 25,
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

        $response = $this->actingAs($student)
            ->withSession(['_token' => 'test-token'])
            ->withHeaders([
                'X-CSRF-TOKEN' => 'test-token',
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->postJson('/facility/scan', [
                'facility_id' => $facility->id,
                'qr_data' => json_encode([
                    'student_id' => $student->student_id,
                    'user_id' => $student->id,
                ]),
            ]);

        $response->assertStatus(400)
            ->assertJsonFragment([
                'error' => 'This facility is currently closed and cannot accept scans.',
            ]);

        $this->assertSame(0, AttendanceLog::count());
        $this->assertSame(0, $facility->fresh()->current_occupancy);
    }

    public function test_admin_overview_calendar_payload_includes_pending_and_approved_reservations(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $student = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $pendingFacility = Facility::create([
            'room_name' => 'Pending Booking Room',
            'building' => 'North Wing',
            'capacity' => 25,
            'is_borrowable' => false,
            'current_occupancy' => 0,
            'status' => 'open',
            'status_overridden' => false,
            'opening_time' => '07:00:00',
            'closing_time' => '21:00:00',
            'lunch_start' => '12:00:00',
            'lunch_end' => '13:00:00',
            'lunch_mode' => 'scheduled',
            'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
        ]);

        $approvedFacility = Facility::create([
            'room_name' => 'Approved Booking Room',
            'building' => 'South Wing',
            'capacity' => 40,
            'is_borrowable' => false,
            'current_occupancy' => 0,
            'status' => 'open',
            'status_overridden' => false,
            'opening_time' => '07:00:00',
            'closing_time' => '21:00:00',
            'lunch_start' => '12:00:00',
            'lunch_end' => '13:00:00',
            'lunch_mode' => 'scheduled',
            'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
        ]);

        Reservation::create([
            'user_id' => $student->id,
            'facility_id' => $pendingFacility->id,
            'start_time' => Carbon::parse('2026-05-28 10:00:00'),
            'end_time' => Carbon::parse('2026-05-28 11:00:00'),
            'status' => 'pending',
        ]);

        Reservation::create([
            'user_id' => $student->id,
            'facility_id' => $approvedFacility->id,
            'start_time' => Carbon::parse('2026-05-24 14:00:00'),
            'end_time' => Carbon::parse('2026-05-24 15:00:00'),
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)->get('/system-admin');

        $response->assertStatus(200)
            ->assertSee('const calendarReservations =', false)
            ->assertSee('"status":"pending"', false)
            ->assertSee('"status":"approved"', false);
    }
}
