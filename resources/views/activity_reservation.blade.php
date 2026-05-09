<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My UNISched | Activity Center Reservation</title>
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
                    <li class="active"><a href="{{ route('activity.reservation') }}"><i class="far fa-calendar-alt"></i> <span>Activity Center Reservation</span></a></li>
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
                    <p class="eyebrow">Reservation</p>
                    <h1>Activity Center Booking</h1>
                    <p class="subtitle">Reserve the Activity Center and view upcoming approved bookings in one place.</p>
                </div>
                <div class="user-profile">
                    <span class="year-badge">2026 A.Y.</span>
                    <a href="{{ route('profile.edit') }}" class="avatar-link">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('profile_pictures/' . auth()->user()->profile_picture) }}" alt="Profile" class="avatar">
                        @else
                            <img src="https://via.placeholder.com/48" alt="Profile" class="avatar">
                        @endif
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
                <div class="card status-card green">
                    <div class="card-info">
                        <h3>{{ $facility->room_name }}</h3>
                        <p>{{ $facility->building }}</p>
                        <p>Capacity: {{ $facility->capacity }}</p>
                        <p>Current occupancy: {{ $facility->current_occupancy }}</p>
                    </div>
                    <div class="progress-circle" data-percent="{{ round(($facility->current_occupancy / max($facility->capacity, 1)) * 100) }}">
                        <svg><circle cx="35" cy="35" r="30"></circle><circle cx="35" cy="35" r="30"></circle></svg>
                        <div class="number">{{ round(($facility->current_occupancy / max($facility->capacity, 1)) * 100) }}%</div>
                    </div>
                </div>

                <div class="widget">
                    <div class="widget-header">Reserve the Activity Center</div>
                    <form id="reservation-form">
                        <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                        <label>Start Date & Time</label>
                        <input type="datetime-local" name="start_time" required>
                        <label>End Date & Time</label>
                        <input type="datetime-local" name="end_time" required>
                        <button type="submit" class="btn">Submit Reservation</button>
                    </form>
                    <div id="reservation-result" class="empty-state" style="margin-top: 16px;"></div>
                </div>

                <div class="widget">
                    <div class="widget-header">Upcoming Approved Reservations</div>
                    @php $approved = $facility->reservations()->where('status', 'approved')->where('start_time', '>', now())->orderBy('start_time')->get(); @endphp
                    @if($approved->isEmpty())
                        <p class="empty-state">No approved bookings for this facility yet.</p>
                    @else
                        <ul class="list-clean">
                            @foreach($approved as $reservation)
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
    <script>
        // Clean up expired reservations on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route('reservations.cleanup') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    console.log('Expired reservations cleaned up');
                }
            }).catch(error => {
                console.error('Error cleaning up expired reservations:', error);
            });
        });
    </script>
    <script>
        document.getElementById('reservation-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('/reservations', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(async response => {
                let data = {};
                try {
                    data = await response.json();
                } catch (error) {
                    console.error('Failed to parse JSON response:', error);
                    document.getElementById('reservation-result').innerText = 'Unable to submit reservation at this time.';
                    document.getElementById('reservation-result').style.color = '#b32f2d';
                    return;
                }

                document.getElementById('reservation-result').innerText = data.message || data.error || 'Unexpected response.';
                if (response.ok) {
                    document.getElementById('reservation-result').style.color = '#2f7b55';
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    document.getElementById('reservation-result').style.color = '#b32f2d';
                }
            })
            .catch(error => {
                console.error('Reservation submit failed:', error);
                document.getElementById('reservation-result').innerText = 'Unable to submit reservation. Please try again.';
                document.getElementById('reservation-result').style.color = '#b32f2d';
            });
        });
    </script>
</body>
</html>
