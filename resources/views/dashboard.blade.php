<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My UNISched | Facility Status Overview</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard_enhanced.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
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

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <div>
                    <p class="eyebrow">Dashboard</p>
                    <h1>Welcome back, {{ auth()->user()->name }}.</h1>
                    <p class="subtitle">Your reservations, equipment status, and facility availability are all updated live.</p>
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

            <h3 class="section-label">Facility Status</h3>
            <section class="facility-grid">
                @foreach($facilities as $facility)
                    @php
                        $percent = $facility->capacity > 0 ? round(($facility->current_occupancy / $facility->capacity) * 100) : 0;
                        $statusClass = $facility->status === 'open' ? 'green' : ($facility->status === 'closed' ? 'red' : 'yellow');
                        $statusText = ucfirst($facility->status);
                        $statusChipClass = 'status-' . $facility->status;
                    @endphp
                    <div class="card status-card {{ $statusClass }}" onclick="window.location.href='{{ route('facility.calendar', $facility->id) }}'">
                        <div class="card-info">
                            <h3>{{ $facility->room_name }}</h3>
                            <p>{{ $facility->building }}</p>
                            <p>Status: <span class="status-chip {{ $statusChipClass }}">{{ $statusText }}</span></p>
                            <p>Occupancy: <strong>{{ $percent }}%</strong></p>
                        </div>
                        <div class="progress-circle" data-percent="{{ $percent }}">
                            <svg><circle cx="35" cy="35" r="30"></circle><circle cx="35" cy="35" r="30"></circle></svg>
                            <div class="number">{{ $percent }}%</div>
                        </div>
                    </div>
                @endforeach

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
                                            <p class="item-subtitle">{{ $reservation->start_time->format('M d, H:i') }} — {{ $reservation->end_time->format('H:i') }}</p>
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
        </main>
    </div>
    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
</body>
</html>
