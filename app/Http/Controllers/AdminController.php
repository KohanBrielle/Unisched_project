<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Facility;
use App\Models\Reservation;
use App\Models\BorrowedEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin_dashboard');
    }

    public function makeAdmin($userId)
    {
        $user = User::findOrFail($userId);

        // Prevent users from making themselves admin
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

        // Prevent users from removing their own admin status
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

        // Prevent users from deleting themselves
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

        // Send notification email to the user
        try {
            Mail::raw(
                "Your reservation for {$facility->room_name} has been approved!\n\n" .
                "Date & Time: {$reservation->start_time->format('M d, Y H:i')} - {$reservation->end_time->format('H:i')}\n" .
                "Location: {$facility->building}\n\n" .
                "Please arrive on time. Thank you for using UNISched!",
                function ($message) use ($reservation) {
                    $message->to($reservation->user->email)
                            ->subject('Reservation Approved - UNISched');
                }
            );
        } catch (\Exception $e) {
            // Log the error but don't fail the approval
            \Log::error('Failed to send reservation approval email: ' . $e->getMessage());
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

            if (!$borrowing->save()) {
                return response()->json(['error' => 'Failed to save the borrow approval.'], 500);
            }

            return response()->json(['message' => 'Borrow request approved']);
        } catch (\Exception $e) {
            \Log::error('Error approving borrow request: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while approving the borrow request.'], 500);
        }
    }

    public function approveReturnRequest($borrowingId)
    {
        try {
            $borrowing = BorrowedEquipment::findOrFail($borrowingId);

            if (!$borrowing->return_requested) {
                return response()->json(['error' => 'No return request found for this item.'], 422);
            }

            if (!$borrowing->is_approved || $borrowing->status !== 'borrowed') {
                return response()->json(['error' => 'This item is not in a valid state for return approval.'], 422);
            }

            $borrowing->status = 'returned';
            $borrowing->return_requested = false;
            $borrowing->returned_at = now();

            if (!$borrowing->save()) {
                return response()->json(['error' => 'Failed to save the return approval.'], 500);
            }

            return response()->json(['message' => 'Return request approved']);
        } catch (\Exception $e) {
            \Log::error('Error approving return request: ' . $e->getMessage());
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
        
        $validated = $request->validate([
            'room_name' => 'sometimes|string|max:255',
            'building' => 'sometimes|string|max:255',
            'capacity' => 'sometimes|integer|min:1',
            'current_occupancy' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:open,closed,reserved',
        ]);

        // Validate current_occupancy doesn't exceed capacity
        $capacity = $validated['capacity'] ?? $facility->capacity;
        $currentOccupancy = $validated['current_occupancy'] ?? $facility->current_occupancy;
        
        if ($currentOccupancy > $capacity) {
            return response()->json(
                ['error' => "Current occupancy ($currentOccupancy) cannot exceed capacity ($capacity)"],
                422
            );
        }

        if (array_key_exists('status', $validated)) {
            $validated['status_overridden'] = true;
        }

        $facility->update($validated);

        return response()->json(['message' => 'Facility updated successfully', 'facility' => $facility]);
    }
}
