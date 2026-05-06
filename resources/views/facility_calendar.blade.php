<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My UNISched | {{ $facility->room_name }} Calendar</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard_enhanced.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .calendar-wrapper {
            display: grid;
            grid-template-columns: 1.7fr 1fr;
            gap: 20px;
        }
        .calendar-box {
            background: #fff;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 22px 50px rgba(80, 56, 155, 0.11);
        }
        .month-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .month-nav button {
            border: none;
            background: var(--primary-purple);
            color: white;
            padding: 10px 16px;
            border-radius: 16px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .month-nav button:hover {
            transform: translateY(-1px);
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 14px;
        }
        .day-header,
        .calendar-day {
            padding: 16px;
            border-radius: 18px;
            min-height: 110px;
        }
        .day-header {
            background: #f6f2ff;
            color: var(--primary-purple);
            text-align: center;
            font-weight: 700;
        }
        .calendar-day {
            background: #fdfbff;
            border: 1px solid #eeeaf8;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .calendar-day.reserved {
            background: linear-gradient(180deg, #ffe7e7 0%, #f7d1d1 100%);
            border-color: #f0c6c6;
        }
        .calendar-day.available {
            background: linear-gradient(180deg, #eff7ff 0%, #e1edff 100%);
            border-color: #d4e3ff;
        }
        .day-number {
            font-size: 1rem;
            font-weight: 700;
        }
        .reservation-info {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 10px;
            line-height: 1.45;
        }
        .details-panel {
            display: grid;
            gap: 18px;
        }
        .details-panel .widget {
            padding: 22px;
        }
        .legend {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 18px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }
        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            display: inline-block;
        }
    </style>
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
                    <li><a href="{{ route('system.admin') }}"><i class="fas fa-cog"></i> <span>System Admin</span></a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <div>
                    <p class="eyebrow">Reservation Calendar</p>
                    <h1>{{ $facility->room_name }}</h1>
                    <p class="subtitle">Browse approved bookings on this month’s calendar and see which dates are already reserved.</p>
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

            <div class="calendar-wrapper">
                <div class="calendar-box">
                    <div class="month-nav">
                        <button onclick="previousMonth()"><i class="fas fa-chevron-left"></i></button>
                        <h2 id="month-label"></h2>
                        <button onclick="nextMonth()"><i class="fas fa-chevron-right"></i></button>
                    </div>

                    <div class="calendar-grid" id="calendar">
                        <!-- Days will render here -->
                    </div>

                    <div class="legend">
                        <div class="legend-item"><span class="legend-dot" style="background:#d4e3ff"></span> Available</div>
                        <div class="legend-item"><span class="legend-dot" style="background:#f0c6c6"></span> Reserved</div>
                    </div>
                </div>

                <div class="details-panel">
                    <div class="widget">
                        <div class="widget-header">Reservation Details</div>
                        <ul class="list-clean">
                            @forelse($facility->reservations()->where('status', 'approved')->orderBy('start_time')->get() as $reservation)
                                <li>
                                    <div class="item-title">{{ $reservation->user->name }}</div>
                                    <p class="item-subtitle">{{ $reservation->start_time->format('M d, H:i') }} — {{ $reservation->end_time->format('H:i') }}</p>
                                </li>
                            @empty
                                <li><p class="empty-state">No approved bookings have been added yet.</p></li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="widget">
                        <div class="widget-header">Room Summary</div>
                        <p class="item-subtitle">Building: {{ $facility->building }}</p>
                        <p class="item-subtitle">Capacity: {{ $facility->capacity }}</p>
                        <p class="item-subtitle">Current occupancy: {{ $facility->current_occupancy }}</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let currentDate = new Date();
        const reservations = {!! json_encode($facility->reservations()->where('status', 'approved')->get()->map(function ($r) {
            return [
                'start' => $r->start_time->format('Y-m-d'),
                'end' => $r->end_time->format('Y-m-d'),
                'user' => $r->user->name,
                'time' => $r->start_time->format('H:i') + ' - ' + $r->end_time->format('H:i'),
            ];
        })) !!};

        const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        function generateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const calendar = document.getElementById('calendar');
            calendar.innerHTML = '';

            document.getElementById('month-label').textContent = currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

            daysOfWeek.forEach(dayName => {
                const header = document.createElement('div');
                header.className = 'day-header';
                header.textContent = dayName;
                calendar.appendChild(header);
            });

            for (let i = 0; i < firstDay.getDay(); i++) {
                const filler = document.createElement('div');
                filler.className = 'calendar-day';
                filler.style.visibility = 'hidden';
                calendar.appendChild(filler);
            }

            for (let day = 1; day <= lastDay.getDate(); day++) {
                const date = new Date(year, month, day);
                const dateStr = date.toISOString().slice(0, 10);
                const activeReservation = reservations.find(r => r.start <= dateStr && dateStr <= r.end);
                const dayElement = document.createElement('div');
                dayElement.className = `calendar-day ${activeReservation ? 'reserved' : 'available'}`;

                const number = document.createElement('div');
                number.className = 'day-number';
                number.textContent = day;
                dayElement.appendChild(number);

                if (activeReservation) {
                    const info = document.createElement('div');
                    info.className = 'reservation-info';
                    info.innerHTML = `<strong>${activeReservation.user}</strong><br>${activeReservation.time}`;
                    dayElement.appendChild(info);
                }

                calendar.appendChild(dayElement);
            }
        }

        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar();
        }

        generateCalendar();
    </script>
</body>
</html>
