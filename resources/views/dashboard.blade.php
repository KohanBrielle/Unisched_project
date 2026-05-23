<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My UNISched | Facility Status Overview</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard_enhanced.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-overview-card {
            background: linear-gradient(135deg, rgba(95, 45, 145, 0.1), rgba(146, 104, 255, 0.14));
            border-radius: 24px;
            padding: 24px;
            border: 1px solid rgba(95, 45, 145, 0.12);
            margin-bottom: 22px;
            box-shadow: 0 22px 50px rgba(80, 56, 155, 0.11);
        }

        .dashboard-overview-card h2 {
            margin: 4px 0 8px;
            font-size: 1.4rem;
        }

        .dashboard-overview-card .subtitle {
            margin: 0;
            color: var(--text-muted);
            max-width: 760px;
        }

        .dashboard-overview-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-top: 18px;
        }

        .overview-stat-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            box-shadow: 0 14px 30px rgba(80, 56, 155, 0.08);
        }

        .overview-stat-card strong {
            font-size: 1.6rem;
            color: var(--primary-purple);
        }

        .overview-stat-card span {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .user-calendar-panel {
            background: #fff;
            border-radius: 24px;
            padding: 24px;
            margin-top: 22px;
            box-shadow: 0 22px 50px rgba(80, 56, 155, 0.11);
        }

        .user-calendar-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(260px, 1fr);
            gap: 18px;
            align-items: start;
        }

        .user-calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 10px;
        }

        .user-calendar-grid .day-header,
        .user-calendar-grid .user-calendar-day {
            border-radius: 18px;
            padding: 12px;
            min-height: 92px;
        }

        .user-calendar-grid .day-header {
            min-height: auto;
            background: #f5efff;
            color: var(--primary-purple);
            text-align: center;
            font-weight: 800;
        }

        .user-calendar-grid .user-calendar-day {
            background: #fcfbff;
            border: 1px solid #eee9f8;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 8px;
        }

        .user-calendar-grid .user-calendar-day.available {
            background: linear-gradient(180deg, #f7faff 0%, #edf4ff 100%);
        }

        .user-calendar-grid .user-calendar-day.reserved {
            background: linear-gradient(180deg, #fff4f4 0%, #ffe4e4 100%);
        }

        .user-calendar-day-number {
            font-weight: 800;
            color: var(--text-primary);
        }

        .user-calendar-meta,
        .user-calendar-tag {
            font-size: 0.78rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .user-calendar-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 800;
            color: var(--primary-purple);
        }

        .user-calendar-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: currentColor;
            display: inline-block;
        }

        .user-calendar-summary {
            background: #fbf9ff;
            border-radius: 20px;
            padding: 18px;
            border: 1px solid #eee9f8;
        }

        .user-calendar-summary h4 {
            margin-top: 0;
            margin-bottom: 12px;
        }

        .user-calendar-summary-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 12px;
        }

        .user-calendar-summary-item {
            padding: 12px 14px;
            border-radius: 16px;
            background: #fff;
            border: 1px solid #efe8fb;
        }

        .user-calendar-summary-item strong {
            display: block;
            margin-bottom: 4px;
        }

        @media (max-width: 1100px) {
            .dashboard-overview-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .user-calendar-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 760px) {
            .dashboard-overview-grid {
                grid-template-columns: 1fr;
            }
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
                    <li class="active"><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> <span>Facility Status Overview</span></a></li>
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
                    <p class="eyebrow">Dashboard</p>
                    <h1>Welcome back, {{ auth()->user()->name }}.</h1>
                    <p class="subtitle">Live facility status, occupancy, and guidance are refreshed automatically.</p>
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

            <div class="dashboard-overview-card">
                <p class="eyebrow">User Overview</p>
                <h2>Stay on top of your campus day</h2>
                <p class="subtitle">A quick snapshot of the facilities, reservations, and equipment activity that matter most to you.</p>
                <div class="dashboard-overview-grid">
                    <div class="overview-stat-card">
                        <strong>{{ $facilities->count() }}</strong>
                        <span>Facilities available to review</span>
                    </div>
                    <div class="overview-stat-card">
                        <strong>{{ $activeReservations->count() }}</strong>
                        <span>Active reservations</span>
                    </div>
                    <div class="overview-stat-card">
                        <strong>{{ $borrowedEquipment->count() }}</strong>
                        <span>Borrowed equipment items</span>
                    </div>
                    <div class="overview-stat-card">
                        <strong>{{ $conflictAlerts->count() }}</strong>
                        <span>Conflict alerts to review</span>
                    </div>
                </div>
            </div>

            <h3 class="section-label">Facility Status</h3>
            <section class="facility-grid">
                @forelse($facilities as $facility)
                    @php
                        $percent = $facility->capacity > 0 ? round(($facility->current_occupancy / $facility->capacity) * 100) : 0;
                        $statusClass = match ($facility->computed_status) {
                            'open' => 'status-card-open',
                            'lunch_break' => 'status-card-lunch',
                            'closed' => 'status-card-closed',
                            default => 'status-card-occupied',
                        };
                    @endphp
                    <div class="card status-card {{ $statusClass }}" data-facility-id="{{ $facility->id }}" data-facility-name="{{ $facility->room_name }}" data-assistance-required="{{ $facility->assistance_required ? '1' : '0' }}" onclick="window.location.href='{{ route('facility.calendar', $facility->id) }}'">
                        <div class="card-info">
                            <div class="card-title-row">
                                <h3>{{ $facility->room_name }}</h3>
                                <span class="status-chip status-{{ $facility->computed_status }}">{{ $facility->status_label }}</span>
                            </div>
                            <p>{{ $facility->building }}</p>
                            <p class="status-message" data-status-message>{{ $facility->status_message }}</p>
                            <p>Occupancy: <strong data-occupancy>{{ $facility->current_occupancy }}</strong> / {{ $facility->capacity }}</p>
                            <div class="assistance-actions" data-assistance-actions>
                                <p class="assistance-note" data-assistance-note {{ $facility->assistance_required ? '' : 'hidden' }}>Need access or a key? Submit an assistance request.</p>
                                <button type="button" class="assistance-button" data-assistance-button {{ $facility->assistance_required ? '' : 'hidden' }} onclick="event.stopPropagation(); openAssistanceModal(this.dataset.facilityId, this.dataset.facilityName);" data-facility-id="{{ $facility->id }}" data-facility-name="{{ $facility->room_name }}">Request assistance</button>
                            </div>
                        </div>
                        <div class="progress-circle" data-percent="{{ $percent }}">
                            <svg><circle cx="35" cy="35" r="30"></circle><circle cx="35" cy="35" r="30"></circle></svg>
                            <div class="number" data-percent-label>{{ $percent }}%</div>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-info">
                            <h3>No facilities configured yet</h3>
                            <p>Facility data will appear here once the admin creates them.</p>
                        </div>
                    </div>
                @endforelse

                <div class="widget stats-widget">
                    <div class="widget-header">Quick Overview</div>
                    <div class="stats-grid">
                        <div class="stat-block">
                            <span>{{ $facilities->count() }}</span>
                            <p>Facilities</p>
                        </div>
                        <div class="stat-block">
                            <span>{{ $activeReservations->count() }}</span>
                            <p>Active reservations</p>
                        </div>
                        <div class="stat-block">
                            <span>{{ $borrowedEquipment->count() }}</span>
                            <p>Borrowed items</p>
                        </div>
                        <div class="stat-block">
                            <span>{{ $conflictAlerts->count() }}</span>
                            <p>Conflict alerts</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bottom-widgets">
                <div class="widget">
                    <div class="widget-header">My Active Reservations</div>
                    @if($activeReservations->isEmpty())
                        <p class="empty-state">No active reservations yet. Reserve a facility to see it here.</p>
                    @else
                        <ul class="list-clean">
                            @foreach($activeReservations as $reservation)
                                <li>
                                    <div class="reservation-item">
                                        <div class="reservation-info">
                                            <div class="item-title">{{ $reservation->facility->room_name }}</div>
                                            <p class="item-subtitle">{{ $reservation->start_time->format('M d, H:i') }} � {{ $reservation->end_time->format('H:i') }}</p>
                                        </div>
                                        @if($reservation->canBeCancelled())
                                            <button class="btn-cancel" onclick="cancelReservation({{ $reservation->id }})">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="widget">
                    <div class="widget-header">Borrowed Equipment</div>
                    @if($borrowedEquipment->isEmpty())
                        <p class="empty-state">No equipment currently borrowed.</p>
                    @else
                        <ul class="list-clean">
                            @foreach($borrowedEquipment as $item)
                                <li>
                                    <div class="equipment-item {{ $item->isOverdue() ? 'overdue' : '' }}">
                                        <div class="equipment-info">
                                            <div class="item-title">{{ $item->equipment_name }}</div>
                                            <p class="item-subtitle">Return by {{ $item->return_date->format('M d, Y') }}</p>
                                            @if($item->isOverdue())
                                                <span class="overdue-badge">Overdue</span>
                                            @endif
                                        </div>
                                        <button class="btn-return" onclick="returnEquipment({{ $item->id }})">
                                            <i class="fas fa-undo"></i> Return
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="widget">
                    <div class="widget-header">Conflict Alerts</div>
                    @if($conflictAlerts->isEmpty())
                        <p class="empty-state">No booking conflicts detected.</p>
                    @else
                        <ul class="list-clean">
                            @foreach($conflictAlerts as $conflict)
                                <li>
                                    <div class="item-title">{{ $conflict->facility->room_name }}</div>
                                    <p class="item-subtitle">Pending request by {{ $conflict->user->name }} from {{ $conflict->start_time->format('M d, H:i') }} to {{ $conflict->end_time->format('H:i') }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </section>

            <section class="user-calendar-panel">
                <div class="panel-head" style="margin-bottom: 12px;">
                    <div>
                        <p class="eyebrow">Your Calendar</p>
                        <h3 style="margin: 4px 0 0;">Reservation timeline</h3>
                        <p class="subtitle" style="margin-top: 6px;">Track your upcoming bookings for the current month and see which dates are already reserved.</p>
                    </div>
                    <div style="display:flex; gap:10px; align-items:center;">
                        <button type="button" id="userCalendarPrevBtn" class="primary-button" style="padding:10px 14px;">Previous</button>
                        <strong id="userCalendarMonthLabel">Current month</strong>
                        <button type="button" id="userCalendarNextBtn" class="primary-button" style="padding:10px 14px;">Next</button>
                    </div>
                </div>
                <div class="user-calendar-layout">
                    <div>
                        <div class="user-calendar-grid" id="userCalendarGrid"></div>
                    </div>
                    <div class="user-calendar-summary">
                        <h4>Upcoming reservations</h4>
                        <ul class="user-calendar-summary-list" id="userCalendarSummaryList"></ul>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <div id="assistanceModal" class="modal-backdrop" hidden>
        <div class="modal-card">
            <div class="modal-header">
                <div>
                    <p class="eyebrow">Need help?</p>
                    <h2>Submit an assistance request</h2>
                </div>
                <button type="button" class="modal-close" onclick="closeAssistanceModal()" aria-label="Close assistance modal">&times;</button>
            </div>
            <p class="modal-subtitle">Tell us what you need for <strong id="assistanceFacilityName"></strong>.</p>
            <form id="assistanceForm">
                <input type="hidden" id="assistanceFacilityId">
                <label for="assistanceMessage" class="modal-label">How can we help?</label>
                <textarea id="assistanceMessage" class="modal-textarea" rows="5" maxlength="1000" placeholder="Example: The doors are locked and I need access to this room."></textarea>
                <p id="assistanceFeedback" class="modal-feedback" aria-live="polite"></p>
                <div class="modal-actions">
                    <button type="button" class="secondary-button" onclick="closeAssistanceModal()">Cancel</button>
                    <button type="submit" class="primary-button">Submit request</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
    @php
        $userCalendarReservations = $activeReservations->map(function ($reservation) {
            return [
                'start' => $reservation->start_time->format('Y-m-d'),
                'end' => $reservation->end_time->format('Y-m-d'),
                'facility' => optional($reservation->facility)->room_name ?? 'Facility',
                'time' => $reservation->start_time->format('H:i') . ' - ' . $reservation->end_time->format('H:i'),
                'status' => ucfirst($reservation->status),
            ];
        })->values();
    @endphp
    <script>
        const userCalendarReservations = @json($userCalendarReservations);
        const userCalendarGrid = document.getElementById('userCalendarGrid');
        const userCalendarSummaryList = document.getElementById('userCalendarSummaryList');
        const userCalendarMonthLabel = document.getElementById('userCalendarMonthLabel');
        const userCalendarPrevBtn = document.getElementById('userCalendarPrevBtn');
        const userCalendarNextBtn = document.getElementById('userCalendarNextBtn');
        let currentUserCalendarDate = new Date();

        function formatMonth(date) {
            return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        }

        function getMonthKey(date) {
            return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
        }

        function getLocalDateString(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function isReservedForDate(event, dateString) {
            return event.start <= dateString && dateString <= event.end;
        }

        function renderUserCalendar() {
            const year = currentUserCalendarDate.getFullYear();
            const month = currentUserCalendarDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const monthKey = getMonthKey(currentUserCalendarDate);
            const monthReservations = (userCalendarReservations || []).filter((reservation) => reservation.start?.slice(0, 7) === monthKey);

            userCalendarMonthLabel.textContent = formatMonth(currentUserCalendarDate);
            userCalendarGrid.innerHTML = '';

            ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach((label) => {
                const header = document.createElement('div');
                header.className = 'day-header';
                header.textContent = label;
                userCalendarGrid.appendChild(header);
            });

            for (let i = 0; i < firstDay.getDay(); i++) {
                const spacer = document.createElement('div');
                spacer.className = 'user-calendar-day';
                spacer.style.visibility = 'hidden';
                userCalendarGrid.appendChild(spacer);
            }

            for (let day = 1; day <= lastDay.getDate(); day++) {
                const date = new Date(year, month, day);
                const dateString = getLocalDateString(date);
                const matchingReservations = monthReservations.filter((reservation) => isReservedForDate(reservation, dateString));
                const cell = document.createElement('div');
                cell.className = `user-calendar-day ${matchingReservations.length ? 'reserved' : 'available'}`;
                cell.innerHTML = `
                    <span class="user-calendar-day-number">${day}</span>
                    ${matchingReservations.length
                        ? `<span class="user-calendar-tag"><span class="user-calendar-dot"></span>${matchingReservations.length} booking${matchingReservations.length > 1 ? 's' : ''}</span>`
                        : '<span class="user-calendar-meta">Open</span>'}
                `;

                if (matchingReservations.length) {
                    const meta = document.createElement('div');
                    meta.className = 'user-calendar-meta';
                    meta.textContent = matchingReservations
                        .map((reservation) => `${reservation.facility} • ${reservation.time}`)
                        .join(' | ');
                    cell.appendChild(meta);
                }

                userCalendarGrid.appendChild(cell);
            }

            const todayKey = getLocalDateString(new Date());
            const summaryItems = monthReservations
                .filter((reservation) => reservation.start >= todayKey)
                .sort((a, b) => a.start.localeCompare(b.start))
                .slice(0, 5);

            if (!summaryItems.length) {
                userCalendarSummaryList.innerHTML = '<li class="calendar-empty">No reservations are scheduled for this month.</li>';
                return;
            }

            userCalendarSummaryList.innerHTML = summaryItems
                .map((reservation) => `
                    <li class="user-calendar-summary-item">
                        <strong>${reservation.facility}</strong>
                        <span class="user-calendar-meta">${reservation.time}</span><br>
                        <span class="user-calendar-meta">${reservation.status}</span><br>
                        <span class="user-calendar-meta">${reservation.start}</span>
                    </li>
                `)
                .join('');
        }

        userCalendarPrevBtn?.addEventListener('click', () => {
            currentUserCalendarDate = new Date(currentUserCalendarDate.getFullYear(), currentUserCalendarDate.getMonth() - 1, 1);
            renderUserCalendar();
        });

        userCalendarNextBtn?.addEventListener('click', () => {
            currentUserCalendarDate = new Date(currentUserCalendarDate.getFullYear(), currentUserCalendarDate.getMonth() + 1, 1);
            renderUserCalendar();
        });

        renderUserCalendar();
    </script>
</body>
</html>
