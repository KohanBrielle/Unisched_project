<?php

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\BorrowedEquipment;
use Illuminate\Http\Request;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Route::middleware(['auth', 'verified', 'nocache'])->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', function () {
        $facilities = Facility::with(['reservations' => function ($query) {
            $query->where('status', 'approved')->where('end_time', '>', now());
        }])->get();

        $activeReservations = auth()->user()->reservations()
            ->where('status', 'approved')
            ->where('end_time', '>', now())
            ->with('facility')
            ->get();

        $borrowedEquipment = BorrowedEquipment::where('user_id', auth()->id())
            ->where('status', 'borrowed')
            ->get();

        $conflictAlerts = Reservation::where('status', 'pending')
            ->get()
            ->filter(function ($pending) {
                return Reservation::where('status', 'approved')
                    ->where('facility_id', $pending->facility_id)
                    ->where(function ($query) use ($pending) {
                        $query->whereBetween('start_time', [$pending->start_time, $pending->end_time])
                            ->orWhereBetween('end_time', [$pending->start_time, $pending->end_time])
                            ->orWhere(function ($inner) use ($pending) {
                                $inner->where('start_time', '<', $pending->start_time)
                                      ->where('end_time', '>', $pending->end_time);
                            });
                    })
                    ->exists();
            });

        return view('dashboard', compact('facilities', 'activeReservations', 'borrowedEquipment', 'conflictAlerts'));
    })->name('dashboard');

    Route::get('/activity-reservation', function () {
        $facility = Facility::where('room_name', 'Activity Center')->first();
        return view('activity_reservation', compact('facility'));
    })->name('activity.reservation');

    Route::get('/library-status', function () {
        $facility = Facility::where('room_name', 'Library')->first();
        return view('facility_status', compact('facility'));
    })->name('library.status');

    Route::get('/gym-status', function () {
        $facility = Facility::where('room_name', 'Gym')->first();
        return view('facility_status', compact('facility'));
    })->name('gym.status');

    Route::get('/canteen-status', function () {
        $facility = Facility::where('room_name', 'Canteen')->first();
        return view('facility_status', compact('facility'));
    })->name('canteen.status');

    Route::get('/bao-status', function () {
        $facility = Facility::where('room_name', 'BAO')->first();
        return view('facility_status', compact('facility'));
    })->name('bao.status');

    Route::get('/equipment-borrowing', function () {
        $equipmentOptions = [
            'Projector' => 'Projector',
            'Sound System' => 'Sound System',
            'Microphones' => 'Microphones',
            'Whiteboard' => 'Whiteboard',
        ];

        $borrowedEquipment = BorrowedEquipment::where('status', 'borrowed')->get();
        $userBorrowed = BorrowedEquipment::where('user_id', auth()->id())
            ->where('status', 'borrowed')
            ->get();

        return view('equipment_borrowing', compact('equipmentOptions', 'borrowedEquipment', 'userBorrowed'));
    })->name('equipment.borrowing');

    Route::get('/facility/{id}/calendar', function ($id) {
        $facility = Facility::findOrFail($id);
        return view('facility_calendar', compact('facility'));
    })->name('facility.calendar');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/facility/scan', [FacilityController::class, 'scan'])->name('facility.scan');

    Route::post('/reservations', function (Request $request) {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        $conflict = Reservation::where('facility_id', $request->facility_id)
            ->where('status', 'approved')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($inner) use ($request) {
                        $inner->where('start_time', '<', $request->start_time)
                              ->where('end_time', '>', $request->end_time);
                    });
            })
            ->exists();

        if ($conflict) {
            return response()->json(['error' => 'This reservation conflicts with an existing approved booking.'], 422);
        }

        Reservation::create([
            'user_id' => auth()->id(),
            'facility_id' => $request->facility_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Reservation submitted successfully']);
    })->name('reservations.store');

    Route::post('/borrowings', function (Request $request) {
        $request->validate([
            'equipment' => 'required|string',
            'return_date' => 'required|date|after:today',
        ]);

        $conflict = BorrowedEquipment::where('equipment_name', $request->equipment)
            ->where('status', 'borrowed')
            ->where('return_date', '>=', now()->toDateString())
            ->exists();

        if ($conflict) {
            return response()->json(['error' => 'This item is already borrowed until a later date.'], 422);
        }

        BorrowedEquipment::create([
            'user_id' => auth()->id(),
            'equipment_name' => $request->equipment,
            'return_date' => $request->return_date,
            'status' => 'borrowed',
        ]);

        return response()->json(['message' => 'Equipment borrowed successfully.']);
    })->name('borrowings.store');
});

Route::middleware(['auth', 'verified', 'admin', 'nocache'])->group(function () {
    Route::post('/admin/users/{id}/make-admin', [AdminController::class, 'makeAdmin']);
    Route::post('/admin/users/{id}/remove-admin', [AdminController::class, 'removeAdmin']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
    Route::delete('/admin/facilities/{id}', [AdminController::class, 'deleteFacility']);
    Route::post('/admin/reservations/{id}/approve', [AdminController::class, 'approveReservation']);
    Route::post('/admin/reservations/{id}/reject', [AdminController::class, 'rejectReservation']);
});

Route::get('/system-admin', [AdminController::class, 'dashboard'])
    ->middleware(['auth', 'verified', 'admin', 'nocache'])
    ->name('system.admin');

require __DIR__.'/auth.php';
