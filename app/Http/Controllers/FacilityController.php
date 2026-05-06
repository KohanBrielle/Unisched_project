<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FacilityController extends Controller
{
    public function scan(Request $request): JsonResponse
    {
        $request->validate([
            'qr_data' => 'required|string',
            'facility_id' => 'required|exists:facilities,id',
        ]);

        $qrData = json_decode($request->qr_data, true);
        if (!$qrData || !isset($qrData['student_id'], $qrData['user_id'])) {
            return response()->json(['error' => 'Invalid QR code'], 400);
        }

        $user = User::where('student_id', $qrData['student_id'])->where('id', $qrData['user_id'])->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $facility = Facility::find($request->facility_id);

        // Check if user has an open attendance log (no time_out)
        $openLog = AttendanceLog::where('user_id', $user->id)
            ->where('facility_id', $facility->id)
            ->whereNull('time_out')
            ->first();

        if ($openLog) {
            // Check out: set time_out and decrement occupancy
            $openLog->update(['time_out' => now()]);
            $facility->decrement('current_occupancy');
            return response()->json(['message' => 'Checked out successfully']);
        } else {
            // Check in: create new log and increment occupancy
            if ($facility->current_occupancy >= $facility->capacity) {
                return response()->json(['error' => 'Facility at full capacity'], 400);
            }
            AttendanceLog::create([
                'user_id' => $user->id,
                'facility_id' => $facility->id,
                'time_in' => now(),
            ]);
            $facility->increment('current_occupancy');
            return response()->json(['message' => 'Checked in successfully']);
        }
    }
}
