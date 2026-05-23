<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My UNISched | Library Status</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard_enhanced.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(180deg, #f8f5ff 0%, #eff0ff 100%);
        }

        .library-page .status-chip.status-open {
            background: rgba(67, 179, 109, 0.12);
            color: #2f7b55;
        }

        .library-page .status-chip.status-closed {
            background: rgba(169, 169, 169, 0.14);
            color: #4d4d4d;
        }

        .library-page .status-chip.status-lunch_break {
            background: rgba(241, 196, 15, 0.16);
            color: #8f6b06;
        }

        .library-page .status-chip.status-reserved {
            background: rgba(95, 45, 145, 0.12);
            color: #5b2ba2;
        }

        .library-page .status-chip.status-in_use {
            background: rgba(124, 58, 237, 0.12);
            color: #5b21b6;
        }

        .library-page .info-panel {
            display: grid;
            gap: 16px;
            background: #faf8ff;
            border-radius: 24px;
            padding: 24px;
            border: 1px solid #eee8ff;
        }

        .library-page .info-panel h3,
        .library-page .widget-header,
        .library-page .card-info h3 {
            margin-top: 0;
            margin-bottom: 8px;
        }

        .library-page .scan-preview {
            min-height: 280px;
            border-radius: 24px;
            display: grid;
            place-items: center;
            padding: 24px;
            text-align: center;
            background: linear-gradient(135deg, #1b1332 0%, #2d1e4b 100%);
            color: rgba(255,255,255,0.95);
            border: 1px solid rgba(255,255,255,0.06);
        }

        .library-page .scan-preview .camera-icon {
            width: 56px;
            height: 56px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.08);
            margin-bottom: 14px;
            font-size: 1.2rem;
        }

        .library-page .scan-result {
            margin-top: 12px;
            color: #2d2055;
            font-weight: 700;
        }

        .library-page .mb-0 {
            margin-bottom: 0;
        }

        .library-page .scan-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .library-page textarea {
            min-height: 160px;
        }

        .library-page .empty-state {
            margin: 0;
            background: #faf8ff;
            border: 1px dashed #e7ddff;
            border-radius: 18px;
            padding: 18px;
            color: var(--text-muted);
        }

        .library-page .item-title {
            margin-bottom: 6px;
        }

        @media (max-width: 760px) {
            .library-page .scan-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container library-page">
        <aside class="sidebar">
            <div class="logo">
                <span class="logo-my">My</span> <span class="logo-uni">UNISched</span>
            </div>
            <nav id="sidebar-nav">
                <ul>
                    <li><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> <span>Facility Status Overview</span></a></li>
                    <li><a href="{{ route('activity.reservation') }}"><i class="far fa-calendar-alt"></i> <span>Activity Center Reservation</span></a></li>
                    <li class="active"><a href="{{ route('library.status') }}"><i class="fas fa-book"></i> <span>Library Status</span></a></li>
                    <li><a href="{{ route('gym.status') }}"><i class="fas fa-dumbbell"></i> <span>Gym Status</span></a></li>
                    <li><a href="{{ route('canteen.status') }}"><i class="fas fa-utensils"></i> <span>Canteen Status</span></a></li>
                    <li><a href="{{ route('bao.status') }}"><i class="fas fa-building"></i> <span>BAO Status</span></a></li>
                    <li><a href="{{ route('equipment.borrowing') }}"><i class="fas fa-tools"></i> <span>Equipment Borrowing</span></a></li>
                    @if(auth()->user()->is_admin)
                        <li><a href="{{ route('system.admin') }}"><i class="fas fa-cog"></i> <span>System Admin</span></a></li>
                    @endif
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <div>
                    <p class="eyebrow">Library Status</p>
                    <h1>{{ $facility->room_name }}</h1>
                    <p class="subtitle">Monitor current availability, attendance activity, and upcoming reservations in one consistent campus view.</p>
                </div>

                <div class="user-profile">
                    <span class="year-badge">2026 A.Y.</span>
                    <a href="{{ route('profile.edit') }}" class="avatar-link">
                        <img src="{{ auth()->user()->profile_picture_url }}" alt="Profile" class="avatar">
                    </a>
                    <div class="user-actions">
                        <a href="{{ route('profile.edit') }}">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Log Out</button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="live-status-banner">
                <span><i class="fas fa-arrows-rotate"></i> Live status updates every 30 seconds</span>
            </div>

            @php
                $upcoming = $facility->reservations()->where('status', 'approved')->where('end_time', '>', now())->orderBy('start_time')->get();
                $activeReservation = $facility->reservations()->where('status', 'approved')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now())
                    ->exists();
                $displayStatus = $facility->status;
                if ($displayStatus === 'open' && $activeReservation) {
                    $displayStatus = 'reserved';
                }
                $occupancyPercent = (int) round(($facility->current_occupancy / max($facility->capacity, 1)) * 100);
                $attendanceCount = $facility->attendanceLogs()->count();
                $upcomingCount = $upcoming->count();
            @endphp

            <div class="facility-grid">
                <div class="card status-card status-card-{{ $displayStatus === 'open' ? 'open' : ($displayStatus === 'lunch_break' ? 'lunch' : ($displayStatus === 'closed' ? 'closed' : 'occupied')) }}" data-facility-id="{{ $facility->id }}" data-facility-name="{{ $facility->room_name }}" data-assistance-required="{{ $facility->assistance_required ? '1' : '0' }}">
                    <div class="card-info">
                        <div class="card-title-row">
                            <h3>Library Snapshot</h3>
                            <span class="status-chip status-{{ $displayStatus }}">{{ ucfirst($displayStatus) }}</span>
                        </div>
                        <p>{{ $facility->building }}</p>
                        <p class="status-message" data-status-message>
                            {{ $displayStatus === 'open' ? 'The library is open and ready for walk-ins.' : ($displayStatus === 'reserved' ? 'A booking is active right now.' : ($displayStatus === 'lunch_break' ? 'A lunch break is currently in effect.' : 'The library is currently closed.')) }}
                        </p>
                        <p>Occupancy: <strong data-occupancy>{{ $facility->current_occupancy }}</strong> / {{ $facility->capacity }}</p>
                    </div>
                    <div class="progress-circle" data-percent="{{ $occupancyPercent }}">
                        <svg><circle cx="35" cy="35" r="30"></circle><circle cx="35" cy="35" r="30"></svg>
                        <div class="number" data-percent-label>{{ $occupancyPercent }}%</div>
                    </div>
                </div>

                <div class="widget info-panel">
                    <div>
                        <div class="widget-header">
                            <span>Recent Attendance Logs</span>
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        @if($attendanceCount === 0)
                            <p class="empty-state">No attendance logs are available yet. Once students check in, their recent activity will appear here.</p>
                        @else
                            <ul class="list-clean">
                                @foreach($facility->attendanceLogs()->latest()->take(5)->get() as $log)
                                    <li>
                                        <p class="item-title">{{ $log->user->name }}</p>
                                        <p class="item-subtitle">@if($log->time_out) Checked out at {{ $log->time_out->format('H:i') }} @else Still in the library @endif</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="widget info-panel">
                    <div>
                        <div class="widget-header">
                            <span>Upcoming Bookings</span>
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        @if($upcomingCount === 0)
                            <p class="empty-state">No approved bookings are scheduled right now. The next reservation will appear here automatically.</p>
                        @else
                            <ul class="list-clean">
                                @foreach($upcoming as $reservation)
                                    <li>
                                        <p class="item-title">{{ $reservation->user->name }}</p>
                                        <p class="item-subtitle">{{ $reservation->start_time->format('M d, H:i') }} � {{ $reservation->end_time->format('H:i') }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
</body>
</html>
