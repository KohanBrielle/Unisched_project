<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My UNISched | {{ $facility->room_name }} Status</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard_enhanced.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <span class="logo-my">My</span> <span class="logo-uni">UNISched</span>
            </div>
            <nav id="sidebar-nav">
                <ul>
                    <li><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> <span>Facility Status Overview</span></a></li>
                    <li><a href="{{ route('activity.reservation') }}"><i class="far fa-calendar-alt"></i> <span>Activity Center Reservation</span></a></li>
                    <li><a href="{{ route('library.status') }}"><i class="fas fa-book"></i> <span>Library Status</span></a></li>
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
                    <p class="eyebrow">Facility Status</p>
                    <h1>{{ $facility->room_name }} Status</h1>
                    <p class="subtitle">Review current occupancy, upcoming bookings, and stay ready to check in with your QR code.</p>
                </div>
                <div class="user-profile">
                    <span class="year-badge">2026 A.Y.</span>
                    <a href="{{ route('profile.edit') }}" class="avatar-link">
                        <img src="https://via.placeholder.com/48" alt="Profile" class="avatar">
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

            <section class="facility-grid">
                @php $upcoming = $facility->reservations()->where('status', 'approved')->where('end_time', '>', now())->orderBy('start_time')->get(); @endphp
                <div class="card status-card {{ $upcoming->isNotEmpty() ? 'red' : 'green' }}">
                    <div class="card-info">
                        <h3>{{ $facility->room_name }}</h3>
                        <p>Building: {{ $facility->building }}</p>
                        <p>Current occupancy: {{ $facility->current_occupancy }} / {{ $facility->capacity }}</p>
                        <p>Status: <span class="status-chip {{ $upcoming->isNotEmpty() ? 'status-reserved' : 'status-open' }}">{{ $upcoming->isNotEmpty() ? 'RESERVED' : 'OPEN' }}</span></p>
                    </div>
                    <div class="progress-circle" data-percent="{{ round(($facility->current_occupancy / max($facility->capacity, 1)) * 100) }}">
                        <svg><circle cx="35" cy="35" r="30"></circle><circle cx="35" cy="35" r="30"></circle></svg>
                        <div class="number">{{ round(($facility->current_occupancy / max($facility->capacity, 1)) * 100) }}%</div>
                    </div>
                </div>

                @if($facility->room_name !== 'Canteen')
                    <div class="widget">
                        <div class="widget-header">Recent Attendance Logs</div>
                        @if($facility->attendanceLogs()->count() === 0)
                            <p class="empty-state">No attendance logs available.</p>
                        @else
                            <ul class="list-clean">
                                @foreach($facility->attendanceLogs()->latest()->take(5)->get() as $log)
                                    <li>
                                        <div class="item-title">{{ $log->user->name }}</div>
                                        <p class="item-subtitle">Checked in at {{ $log->time_in->format('H:i') }}@if($log->time_out) – out at {{ $log->time_out->format('H:i') }}@endif</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <div class="widget">
                    <div class="widget-header">Upcoming Bookings</div>
                    @if($upcoming->isEmpty())
                        <p class="empty-state">No upcoming approved reservations scheduled.</p>
                    @else
                        <ul class="list-clean">
                            @foreach($upcoming as $reservation)
                                <li>
                                    <div class="item-title">{{ $reservation->user->name }}</div>
                                    <p class="item-subtitle">{{ $reservation->start_time->format('M d, H:i') }} — {{ $reservation->end_time->format('H:i') }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </section>
        </main>
    </div>
    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
</body>
</html>
