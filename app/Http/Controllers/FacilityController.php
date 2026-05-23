<?php

namespace App\Http\Controllers;

use App\Models\AssistanceRequest;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function scan(Request $request): JsonResponse
    {
        $request->validate([
            'qr_data' => 'required|string',
            'facility_id' => 'required|exists:facilities,id',
        ]);

        $qrData = json_decode($request->qr_data, true);
        if (! $qrData || ! isset($qrData['student_id'], $qrData['user_id'])) {
            return response()->json(['error' => 'Invalid QR code'], 400);
        }

        $user = User::where('student_id', $qrData['student_id'])->where('id', $qrData['user_id'])->first();
        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $facility = Facility::find($request->facility_id);

        if ($facility->computed_status === 'closed') {
            return response()->json([
                'error' => 'This facility is currently closed and cannot accept scans.',
            ], 400);
        }

        $openLog = \App\Models\AttendanceLog::where('user_id', $user->id)
            ->where('facility_id', $facility->id)
            ->whereNull('time_out')
            ->first();

        if ($openLog) {
            $openLog->update(['time_out' => now()]);
            $facility->decrement('current_occupancy');

            return response()->json(['message' => 'Checked out successfully']);
        }

        if ($facility->current_occupancy >= $facility->capacity) {
            return response()->json(['error' => 'Facility at full capacity'], 400);
        }

        \App\Models\AttendanceLog::create([
            'user_id' => $user->id,
            'facility_id' => $facility->id,
            'time_in' => now(),
        ]);

        $facility->increment('current_occupancy');

        return response()->json(['message' => 'Checked in successfully']);
    }

    public function status(): JsonResponse
    {
        $facilities = Facility::query()
            ->orderBy('room_name')
            ->get()
            ->map(function (Facility $facility) {
                $percent = $facility->capacity > 0
                    ? round(($facility->current_occupancy / $facility->capacity) * 100)
                    : 0;

                return [
                    'id' => $facility->id,
                    'room_name' => $facility->room_name,
                    'building' => $facility->building,
                    'capacity' => $facility->capacity,
                    'current_occupancy' => $facility->current_occupancy,
                    'percent' => $percent,
                    'computed_status' => $facility->computed_status,
                    'status_label' => $facility->status_label,
                    'status_message' => $facility->status_message,
                    'assistance_required' => $facility->assistance_required,
                    'is_borrowable' => (bool) $facility->is_borrowable,
                ];
            });

        return response()->json([
            'facilities' => $facilities,
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    public function submitAssistance(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'message' => 'required|string|min:5|max:1000',
        ]);

        $assistanceRequest = AssistanceRequest::create([
            'user_id' => auth()->id(),
            'facility_id' => $validated['facility_id'],
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Assistance request submitted successfully',
            'assistance_request' => $assistanceRequest,
        ], 201);
    }
}
