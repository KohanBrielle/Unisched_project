<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin_dashboard');
    }

    public function makeAdmin($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_admin = true;
        $user->save();

        return response()->json(['message' => 'User is now an admin']);
    }

    public function removeAdmin($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_admin = false;
        $user->save();

        return response()->json(['message' => 'Admin privileges removed']);
    }

    public function deleteUser($userId)
    {
        User::findOrFail($userId)->delete();
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

        return response()->json(['message' => 'Reservation approved']);
    }

    public function rejectReservation($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->status = 'rejected';
        $reservation->save();

        return response()->json(['message' => 'Reservation rejected']);
    }
}
