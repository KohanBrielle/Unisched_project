<?php

namespace App\Http\Controllers;

use App\Models\AssistanceRequest;
use App\Models\BorrowedEquipment;
use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'users' => collect(),
            'facilities' => collect(),
            'pendingReservations' => collect(),
            'approvedReservations' => collect(),
            'borrowedEquipment' => collect(),
            'pendingBorrowRequests' => collect(),
            'returnRequests' => collect(),
            'assistanceRequests' => collect(),
            'attendanceLogs' => collect(),
        ];

        try {
            $data['users'] = User::query()->orderBy('name')->get();
            $data['facilities'] = Facility::query()->orderBy('room_name')->get();
            $data['pendingReservations'] = Reservation::query()
                ->where('status', 'pending')
                ->with(['user', 'facility'])
                ->orderByDesc('created_at')
                ->get();
            $data['approvedReservations'] = Reservation::query()
                ->where('status', 'approved')
                ->with(['user', 'facility'])
                ->orderBy('start_time')
                ->get();
            $data['borrowedEquipment'] = BorrowedEquipment::query()
                ->where('status', 'borrowed')
                ->where('is_approved', true)
                ->where('return_requested', false)
                ->with('user')
                ->orderByDesc('borrowed_at')
                ->get();
            $data['pendingBorrowRequests'] = BorrowedEquipment::query()
                ->where('status', 'borrowed')
                ->where('is_approved', false)
                ->with('user')
                ->orderByDesc('created_at')
                ->get();
            $data['returnRequests'] = BorrowedEquipment::query()
                ->where('return_requested', true)
                ->with('user')
                ->orderByDesc('updated_at')
                ->get();
            $data['assistanceRequests'] = AssistanceRequest::query()
                ->with(['user', 'facility'])
                ->orderByDesc('created_at')
                ->get();
            $data['attendanceLogs'] = \App\Models\AttendanceLog::query()
                ->with(['user', 'facility'])
                ->orderByDesc('time_in')
                ->get();
        } catch (\Throwable $e) {
            Log::warning('Unable to load admin dashboard data: ' . $e->getMessage());
        }

        return view('admin_dashboard', $data);
    }

    protected function normalizeTimeFields(Request $request): void
    {
        foreach (['opening_time', 'closing_time', 'lunch_start', 'lunch_end'] as $timeField) {
            if (! array_key_exists($timeField, $request->all())) {
                continue;
            }

            $request->merge([
                $timeField => $this->normalizeTimeValue($request->input($timeField)),
            ]);
        }
    }

    protected function normalizeTimeValue(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_string($value)) {
            return $value;
        }

        $normalized = trim($value);

        if ($normalized === '') {
            return null;
        }

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $normalized) === 1) {
            return substr($normalized, 0, 5);
        }

        return $normalized;
    }

    public function storeFacility(Request $request)
    {
        $this->normalizeTimeFields($request);

        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'building' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'current_occupancy' => 'required|integer|min:0',
            'is_borrowable' => 'sometimes|boolean',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'lunch_start' => 'nullable|date_format:H:i',
            'lunch_end' => 'nullable|date_format:H:i',
            'lunch_mode' => 'sometimes|in:scheduled,disabled',
        ]);

        $validated['is_borrowable'] = filter_var($validated['is_borrowable'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $validated['status'] = 'open';
        $validated['status_overridden'] = false;
        $validated['operating_days'] = $validated['operating_days'] ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        $facility = Facility::create($validated);

        return response()->json(['message' => 'Facility created successfully', 'facility' => $facility], 201);
    }

    public function makeAdmin($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot modify your own admin status'], 403);
        }

        $user->is_admin = true;
        $user->save();

        return response()->json(['message' => 'User is now an admin']);
    }

    public function removeAdmin($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot modify your own admin status'], 403);
        }

        $user->is_admin = false;
        $user->save();

        return response()->json(['message' => 'Admin privileges removed']);
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot delete your own account'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }

    public function deleteFacility($facilityId)
    {
        Facility::findOrFail($facilityId)->delete();

        return response()->json(['message' => 'Facility deleted']);
    }

    public function approveReservation($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->status = 'approved';
        $reservation->save();

        $facility = $reservation->facility;
        if ($facility->status === 'open' && $reservation->start_time <= now() && $reservation->end_time > now()) {
            $facility->update(['status' => 'reserved']);
        }

        try {
            Mail::raw(
                "Your reservation for {$facility->room_name} has been approved!\n\n" .
                "Date & Time: {$reservation->start_time->format('M d, Y H:i')} - {$reservation->end_time->format('H:i')}\n" .
                "Location: {$facility->building}\n\n" .
                'Please arrive on time. Thank you for using UNISched!',
                function ($message) use ($reservation) {
                    $message->to($reservation->user->email)
                        ->subject('Reservation Approved - UNISched');
                }
            );
        } catch (\Exception $e) {
            Log::error('Failed to send reservation approval email: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Reservation approved']);
    }

    public function rejectReservation($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->status = 'rejected';
        $reservation->save();

        return response()->json(['message' => 'Reservation rejected']);
    }

    public function deleteReservation($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted']);
    }

    public function approveBorrowing($borrowingId)
    {
        try {
            $borrowing = BorrowedEquipment::findOrFail($borrowingId);

            if ($borrowing->is_approved) {
                return response()->json(['message' => 'This borrow request is already approved.']);
            }

            if ($borrowing->status !== 'borrowed') {
                return response()->json(['error' => 'This item is not in a pending state.'], 422);
            }

            $borrowing->is_approved = true;
            $borrowing->borrowed_at = now();

            if (! $borrowing->save()) {
                return response()->json(['error' => 'Failed to save the borrow approval.'], 500);
            }

            return response()->json(['message' => 'Borrow request approved']);
        } catch (\Exception $e) {
            Log::error('Error approving borrow request: ' . $e->getMessage());

            return response()->json(['error' => 'An error occurred while approving the borrow request.'], 500);
        }
    }

    public function approveReturnRequest($borrowingId)
    {
        try {
            $borrowing = BorrowedEquipment::findOrFail($borrowingId);

            if (! $borrowing->return_requested) {
                return response()->json(['error' => 'No return request found for this item.'], 422);
            }

            if (! $borrowing->is_approved || $borrowing->status !== 'borrowed') {
                return response()->json(['error' => 'This item is not in a valid state for return approval.'], 422);
            }

            $borrowing->status = 'returned';
            $borrowing->return_requested = false;
            $borrowing->returned_at = now();

            if (! $borrowing->save()) {
                return response()->json(['error' => 'Failed to save the return approval.'], 500);
            }

            return response()->json(['message' => 'Return request approved']);
        } catch (\Exception $e) {
            Log::error('Error approving return request: ' . $e->getMessage());

            return response()->json(['error' => 'An error occurred while approving the return request.'], 500);
        }
    }

    public function cleanupExpiredReservations()
    {
        $expiredReservations = Reservation::where('status', 'approved')
            ->where('end_time', '<', now())
            ->get();

        $count = $expiredReservations->count();

        foreach ($expiredReservations as $reservation) {
            $reservation->delete();
        }

        return response()->json(['message' => "Cleaned up {$count} expired reservations"]);
    }

    public function cleanupAttendanceLogs()
    {
        $count = \App\Models\AttendanceLog::count();
        \App\Models\AttendanceLog::truncate();

        return response()->json(['message' => "Cleaned up {$count} attendance log records"]);
    }

    public function updateFacility(Request $request, $facilityId)
    {
        $facility = Facility::findOrFail($facilityId);

        $this->normalizeTimeFields($request);

        $validated = $request->validate([
            'room_name' => 'sometimes|string|max:255',
            'building' => 'sometimes|string|max:255',
            'capacity' => 'sometimes|integer|min:1',
            'current_occupancy' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:open,closed,reserved',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'lunch_start' => 'nullable|date_format:H:i',
            'lunch_end' => 'nullable|date_format:H:i',
            'lunch_mode' => 'sometimes|in:scheduled,disabled',
        ]);

        $capacity = $validated['capacity'] ?? $facility->capacity;
        $currentOccupancy = $validated['current_occupancy'] ?? $facility->current_occupancy;

        if ($currentOccupancy > $capacity) {
            return response()->json(
                ['error' => "Current occupancy ({$currentOccupancy}) cannot exceed capacity ({$capacity})"],
                422
            );
        }

        if (array_key_exists('status', $validated)) {
            $validated['status_overridden'] = true;
        }

        $facility->update($validated);

        return response()->json(['message' => 'Facility updated successfully', 'facility' => $facility->fresh()]);
    }

    public function resolveAssistanceRequest($id)
    {
        $request = AssistanceRequest::findOrFail($id);
        $request->status = 'resolved';
        $request->save();

        return response()->json(['message' => 'Assistance request resolved']);
    }

    public function clearResolvedAssistanceRequest($id)
    {
        $request = AssistanceRequest::where('id', $id)
            ->where('status', 'resolved')
            ->firstOrFail();

        $request->delete();

        return response()->json(['message' => 'Resolved assistance request cleared']);
    }

    public function clearReservationHistory(Request $request)
    {
        $user = auth()->user();
        $count = Reservation::where('user_id', $user->id)->delete();

        return response()->json(['message' => "Cleared {$count} reservations from your history"]);
    }
}
