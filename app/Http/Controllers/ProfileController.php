<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $reservations = $user->reservations()->with('facility')->latest()->get();
        $pendingReservations = $user->reservations()->with('facility')->where('status', 'pending')->get();

        $qrFile = 'qrcodes/qr_' . $user->student_id . '.svg';
        $qrUrl = file_exists(public_path($qrFile)) ? asset($qrFile) : null;

        return view('profile.edit', [
            'user' => $user,
            'reservations' => $reservations,
            'pendingReservations' => $pendingReservations,
            'qrUrl' => $qrUrl,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $request->validate([
                'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete old profile picture if exists
            if ($user->profile_picture && file_exists(public_path('profile_pictures/' . $user->profile_picture))) {
                unlink(public_path('profile_pictures/' . $user->profile_picture));
            }

            // Store new profile picture
            $file = $request->file('profile_picture');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profile_pictures'), $filename);

            $user->profile_picture = $filename;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
