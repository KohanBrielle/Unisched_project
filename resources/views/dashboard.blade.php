<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My UNISched | Facility Status Overview</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f7f1ff',
                            100: '#efe3ff',
                            200: '#dbcbff',
                            300: '#c4a4ff',
                            400: '#a86fff',
                            500: '#8546ff',
                            600: '#6c31e0',
                            700: '#4d2393',
                            800: '#2f155b',
                            900: '#1e0b36'
                        }
                    },
                    boxShadow: {
                        glow: '0 25px 80px rgba(60, 29, 105, 0.22)'
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(129, 81, 214, 0.24), transparent 26%),
                linear-gradient(135deg, #12061f 0%, #1d0b35 45%, #2b1452 100%);
            color: #0f172a;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.86);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .dashboard-nav-link {
            transition: all 180ms ease;
        }

        .dashboard-nav-link:hover,
        .dashboard-nav-link.active {
            background: linear-gradient(135deg, rgba(131, 82, 232, 0.95), rgba(78, 40, 146, 0.98));
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 14px 35px rgba(75, 45, 140, 0.2);
        }

        .dashboard-card {
            transition: transform 180ms ease, box-shadow 180ms ease;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 40px rgba(63, 31, 122, 0.14);
        }

        .status-card-open { border-left: 6px solid #22c55e; }
        .status-card-closed { border-left: 6px solid #a1a1aa; }
        .status-card-lunch, .status-card-occupied { border-left: 6px solid #f59e0b; }

        .status-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.15rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 800;
        }

        .status-open { background: rgba(34, 197, 94, 0.14); color: #166534; }
        .status-closed { background: rgba(161, 161, 170, 0.18); color: #3f3f46; }
        .status-lunch_break { background: rgba(245, 158, 11, 0.18); color: #92400e; }
        .status-reserved, .status-in_use { background: rgba(133, 70, 255, 0.14); color: #5b21b6; }

        .progress-circle {
            position: relative;
            width: 72px;
            height: 72px;
            min-width: 72px;
        }

        .progress-circle svg {
            width: 72px;
            height: 72px;
            transform: rotate(-90deg);
        }

        .progress-circle circle {
            fill: none;
            stroke-width: 7;
            stroke: rgba(133, 70, 255, 0.12);
            cx: 35;
            cy: 35;
            r: 30;
        }

        .progress-circle circle:last-child {
            stroke: #8546ff;
            stroke-dasharray: 188.4;
            stroke-dashoffset: 188.4;
            transition: stroke-dashoffset 0.8s ease;
        }

        .progress-circle .number {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
        }

        .dashboard-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 999px;
            padding: 0.55rem 1rem;
            background: rgba(133, 70, 255, 0.12);
            color: #4d2393;
            font-weight: 700;
        }

        .dashboard-btn {
            transition: all 180ms ease;
        }

        .dashboard-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.03);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 10px;
        }

        .calendar-grid .day-header,
        .calendar-grid .user-calendar-day {
            border-radius: 18px;
            padding: 12px;
            min-height: 92px;
        }

        .calendar-grid .day-header {
            min-height: auto;
            background: #f5efff;
            color: #4d2393;
            text-align: center;
            font-weight: 800;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .calendar-grid .user-calendar-day {
            background: #fcfbff;
            border: 1px solid #eee9f8;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 8px;
        }

        .calendar-grid .user-calendar-day.available {
            background: linear-gradient(180deg, #f7faff 0%, #edf4ff 100%);
        }

        .calendar-grid .user-calendar-day.reserved {
            background: linear-gradient(180deg, #fff4f4 0%, #ffe4e4 100%);
        }

        .user-calendar-day-number {
            font-weight: 800;
            color: #0f172a;
        }

        .user-calendar-meta,
        .user-calendar-tag {
            font-size: 0.78rem;
            line-height: 1.4;
            color: #64748b;
        }

        .user-calendar-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 800;
            color: #4d2393;
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

        .assistance-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 999px;
            padding: 0.55rem 0.9rem;
            background: linear-gradient(135deg, #8546ff, #5b21b6);
            color: #fff;
            font-weight: 800;
            cursor: pointer;
            transition: transform 180ms ease, filter 180ms ease;
        }

        .assistance-button:hover {
            transform: translateY(-1px);
            filter: brightness(1.03);
        }

        .btn-cancel,
        .btn-return {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border: none;
            border-radius: 999px;
            padding: 0.5rem 0.85rem;
            font-weight: 800;
            cursor: pointer;
            transition: transform 180ms ease, filter 180ms ease;
        }

        .btn-cancel {
            background: #fee2e2;
            color: #b91c1c;
        }

        .btn-return {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-cancel:hover,
        .btn-return:hover {
            transform: translateY(-1px);
        }

        .section-label {
            margin: 0 0 0.75rem;
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
        }

        .primary-button, .secondary-button {
            border: none;
            border-radius: 999px;
            padding: 0.65rem 1rem;
            font-weight: 800;
            cursor: pointer;
            transition: transform 180ms ease, filter 180ms ease;
        }

        .primary-button {
            background: linear-gradient(135deg, #8546ff, #5b21b6);
            color: #fff;
        }

        .secondary-button {
            background: rgba(15, 23, 42, 0.06);
            color: #0f172a;
        }

        .primary-button:hover, .secondary-button:hover {
            transform: translateY(-1px);
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: grid;
            place-items: center;
            padding: 1rem;
            z-index: 50;
        }

        .modal-card {
            width: min(100%, 38rem);
            background: rgba(255, 255, 255, 0.98);
            border-radius: 28px;
            padding: 1.5rem;
            box-shadow: 0 25px 80px rgba(60, 29, 105, 0.25);
        }

        .modal-close {
            border: none;
            background: transparent;
            font-size: 1.5rem;
            cursor: pointer;
            color: #475569;
        }

        @media (max-width: 1100px) {
            .facility-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }

            .bottom-widgets {
                grid-template-columns: 1fr !important;
            }

            .user-calendar-layout {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 760px) {
            .facility-grid {
                grid-template-columns: 1fr !important;
            }

            .calendar-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
</head>
<body class="min-h-screen text-slate-900">
    <div class="relative min-h-screen overflow-hidden">
        <video autoplay muted loop playsinline class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-15 blur-[2px]">
            <source src="{{ asset('videos/LSPU.mp4') }}" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(14,8,27,0.88),rgba(36,20,67,0.72),rgba(25,10,48,0.82))]"></div>

        <div class="relative z-10 flex min-h-screen flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:px-8 xl:px-10">
            <aside class="w-full shrink-0 lg:w-80">
                <div class="glass-panel rounded-[28px] border border-white/60 p-4 shadow-glow">
                    <div class="mb-4 flex items-center gap-3 rounded-[22px] bg-[#1e0b36] px-4 py-3 text-white shadow-lg">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10">
                            <i class="fas fa-crown text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[0.65rem] uppercase tracking-[0.3em] text-violet-100/80">My UNISched</p>
                            <p class="text-lg font-semibold">User Console</p>
                        </div>
                    </div>

                    <div class="rounded-[24px] bg-gradient-to-b from-violet-50 to-white p-3">
                        <p class="mb-3 text-[0.68rem] font-bold uppercase tracking-[0.28em] text-slate-500">Quick Navigation</p>
                        <nav id="sidebar-nav">
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('dashboard') }}" class="dashboard-nav-link active flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700">
                                        <i class="fas fa-th-large w-4"></i>
                                        <span>Facility Status Overview</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('activity.reservation') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700">
                                        <i class="far fa-calendar-alt w-4"></i>
                                        <span>Activity Center Reservation</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('library.status') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700">
                                        <i class="fas fa-book w-4"></i>
                                        <span>Library Status</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('gym.status') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700">
                                        <i class="fas fa-dumbbell w-4"></i>
                                        <span>Gym Status</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('canteen.status') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700">
                                        <i class="fas fa-utensils w-4"></i>
                                        <span>Canteen Status</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('bao.status') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700">
                                        <i class="fas fa-building w-4"></i>
                                        <span>BAO Status</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('equipment.borrowing') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700">
                                        <i class="fas fa-tools w-4"></i>
                                        <span>Equipment Borrowing</span>
                                    </a>
                                </li>
                                @if(auth()->user()->is_admin)
                                    <li>
                                        <a href="{{ route('system.admin') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700">
                                            <i class="fas fa-cog w-4"></i>
                                            <span>System Admin</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>

                        <div class="mt-4 rounded-2xl border border-violet-100 bg-violet-50/80 px-4 py-3">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Campus pulse</p>
                            <p class="mt-2 text-sm text-slate-600">A refined control center for your current reservations, facility conditions, and quick access to campus services.</p>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="flex-1">
                <div class="glass-panel rounded-[30px] border border-white/70 p-4 shadow-glow sm:p-6 lg:p-7">
                    <header class="flex flex-col gap-5 border-b border-violet-100 pb-5 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <p class="text-[0.7rem] font-bold uppercase tracking-[0.3em] text-violet-700">Dashboard</p>
                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Welcome back, {{ auth()->user()->name }}.</h1>
                                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-sm font-bold text-emerald-700">
                                    <i class="fas fa-shield-alt"></i>Live updates
                                </span>
                            </div>
                            <p class="mt-2 max-w-2xl text-sm text-slate-600 sm:text-base">Live facility status, occupancy, and your campus activity are refreshed automatically for the current academic cycle.</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <span class="dashboard-pill">
                                <i class="fas fa-calendar-alt"></i> 2026 A.Y.
                            </span>
                            <div class="flex items-center gap-3 rounded-full bg-white/80 px-3 py-2 shadow-sm ring-1 ring-violet-100">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3">
                                    <img src="{{ auth()->user()->profile_picture_url }}" alt="Profile" class="h-10 w-10 rounded-full object-cover ring-2 ring-violet-100">
                                    <div class="text-left">
                                        <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                        <p class="text-[0.7rem] uppercase tracking-[0.2em] text-slate-500">Student / Faculty</p>
                                    </div>
                                </a>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('profile.edit') }}" class="dashboard-btn inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-700 ring-1 ring-violet-100">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dashboard-btn inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                                        <i class="fas fa-right-from-bracket"></i> Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </header>

                    <div class="mt-5 inline-flex items-center gap-2 rounded-full bg-violet-100 px-4 py-2 text-sm font-semibold text-violet-800">
                        <i class="fas fa-arrows-rotate"></i> Live status updates every 30 seconds
                    </div>

                    <section class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-sm text-slate-500">Facilities available</p>
                            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $facilities->count() }}</p>
                            <p class="mt-2 text-sm text-violet-700">Campus spaces to review now</p>
                        </div>
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-sm text-slate-500">Active reservations</p>
                            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $activeReservations->count() }}</p>
                            <p class="mt-2 text-sm text-emerald-700">Your current bookings</p>
                        </div>
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-sm text-slate-500">Borrowed equipment</p>
                            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $borrowedEquipment->count() }}</p>
                            <p class="mt-2 text-sm text-amber-700">Items currently checked out</p>
                        </div>
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-sm text-slate-500">Conflict alerts</p>
                            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $conflictAlerts->count() }}</p>
                            <p class="mt-2 text-sm text-rose-700">Needs review</p>
                        </div>
                    </section>

                    <section class="mt-6">
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-[0.7rem] font-bold uppercase tracking-[0.3em] text-violet-700">Facility Overview</p>
                                <h2 class="mt-2 text-xl font-bold text-slate-900">Campus status at a glance</h2>
                            </div>
                            <p class="max-w-2xl text-sm text-slate-600">Tap any building card to inspect its schedule or submit assistance when you need help gaining access.</p>
                        </div>

                        <div class="facility-grid grid gap-4 xl:grid-cols-3">
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
                                <div class="dashboard-card card status-card {{ $statusClass }} rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80"
                                    data-facility-id="{{ $facility->id }}"
                                    data-facility-name="{{ $facility->room_name }}"
                                    data-assistance-required="{{ $facility->assistance_required ? '1' : '0' }}"
                                    onclick="window.location.href='{{ route('facility.calendar', $facility->id) }}'">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex flex-wrap items-start justify-between gap-3">
                                                <div>
                                                    <h3 class="text-lg font-bold text-slate-900">{{ $facility->room_name }}</h3>
                                                    <p class="mt-1 text-sm text-slate-500">{{ $facility->building }}</p>
                                                </div>
                                                <span class="status-chip status-{{ $facility->computed_status }}">{{ $facility->status_label }}</span>
                                            </div>
                                            <p class="mt-3 text-sm leading-6 text-slate-600 status-message" data-status-message>{{ $facility->status_message }}</p>
                                            <p class="mt-3 text-sm text-slate-600">Occupancy: <strong class="text-slate-900" data-occupancy>{{ $facility->current_occupancy }}</strong> / {{ $facility->capacity }}</p>
                                            <div class="mt-3 flex flex-wrap items-center gap-3" data-assistance-actions>
                                                <p class="text-sm font-semibold text-rose-700 assistance-note" data-assistance-note {{ $facility->assistance_required ? '' : 'hidden' }}>
                                                    Need access or a key? Submit an assistance request.
                                                </p>
                                                <button type="button"
                                                    class="assistance-button"
                                                    data-assistance-button {{ $facility->assistance_required ? '' : 'hidden' }}
                                                    onclick="event.stopPropagation(); openAssistanceModal(this.dataset.facilityId, this.dataset.facilityName);"
                                                    data-facility-id="{{ $facility->id }}"
                                                    data-facility-name="{{ $facility->room_name }}">
                                                    Request assistance
                                                </button>
                                            </div>
                                        </div>
                                        <div class="progress-circle" data-percent="{{ $percent }}">
                                            <svg><circle cx="35" cy="35" r="30"></circle><circle cx="35" cy="35" r="30"></circle></svg>
                                            <div class="number" data-percent-label>{{ $percent }}%</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                                    <h3 class="text-lg font-bold text-slate-900">No facilities configured yet</h3>
                                    <p class="mt-2 text-sm text-slate-600">Facility data will appear here once the admin creates them.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <section class="mt-6 grid gap-4 xl:grid-cols-3">
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-violet-700">My Active Reservations</p>
                            </div>
                            @if($activeReservations->isEmpty())
                                <p class="text-sm leading-6 text-slate-600">No active reservations yet. Reserve a facility to see it here.</p>
                            @else
                                <ul class="space-y-3">
                                    @foreach($activeReservations as $reservation)
                                        <li class="rounded-[18px] bg-violet-50/70 p-3">
                                            <div class="flex flex-wrap items-center justify-between gap-3">
                                                <div>
                                                    <p class="font-bold text-slate-900">{{ $reservation->facility->room_name }}</p>
                                                    <p class="text-sm text-slate-600">{{ $reservation->start_time->format('M d, H:i') }} - {{ $reservation->end_time->format('H:i') }}</p>
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

                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-violet-700">Borrowed Equipment</p>
                            </div>
                            @if($borrowedEquipment->isEmpty())
                                <p class="text-sm leading-6 text-slate-600">No equipment currently borrowed.</p>
                            @else
                                <ul class="space-y-3">
                                    @foreach($borrowedEquipment as $item)
                                        <li class="rounded-[18px] bg-violet-50/70 p-3 {{ $item->isOverdue() ? 'border border-rose-200' : '' }}">
                                            <div class="flex flex-wrap items-center justify-between gap-3">
                                                <div>
                                                    <p class="font-bold text-slate-900">{{ $item->equipment_name }}</p>
                                                    <p class="text-sm text-slate-600">Return by {{ $item->return_date->format('M d, Y') }}</p>
                                                    @if($item->isOverdue())
                                                        <span class="mt-2 inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-[0.7rem] font-bold text-rose-700">Overdue</span>
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

                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-violet-700">Conflict Alerts</p>
                            </div>
                            @if($conflictAlerts->isEmpty())
                                <p class="text-sm leading-6 text-slate-600">No booking conflicts detected.</p>
                            @else
                                <ul class="space-y-3">
                                    @foreach($conflictAlerts as $conflict)
                                        <li class="rounded-[18px] bg-violet-50/70 p-3">
                                            <p class="font-bold text-slate-900">{{ $conflict->facility->room_name }}</p>
                                            <p class="mt-1 text-sm text-slate-600">Pending request by {{ $conflict->user->name }} from {{ $conflict->start_time->format('M d, H:i') }} to {{ $conflict->end_time->format('H:i') }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </section>

                </div>
            </main>
        </div>

        <div id="assistanceModal" class="modal-backdrop" hidden>
            <div class="modal-card">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[0.7rem] font-bold uppercase tracking-[0.3em] text-violet-700">Need help?</p>
                        <h2 class="mt-2 text-xl font-bold text-slate-900">Submit an assistance request</h2>
                    </div>
                    <button type="button" class="modal-close" onclick="closeAssistanceModal()" aria-label="Close assistance modal">&times;</button>
                </div>
                <p class="mt-3 text-sm text-slate-600">Tell us what you need for <strong id="assistanceFacilityName"></strong>.</p>
                <form id="assistanceForm" class="mt-4">
                    <input type="hidden" id="assistanceFacilityId">
                    <label for="assistanceMessage" class="block text-sm font-semibold text-slate-700">How can we help?</label>
                    <textarea id="assistanceMessage" class="mt-2 w-full rounded-[18px] border border-violet-100 bg-violet-50/60 px-4 py-3 text-sm text-slate-700 focus:border-violet-300 focus:outline-none" rows="5" maxlength="1000" placeholder="Example: The doors are locked and I need access to this room."></textarea>
                    <p id="assistanceFeedback" class="mt-3 text-sm text-rose-700" aria-live="polite"></p>
                    <div class="mt-4 flex flex-wrap justify-end gap-3">
                        <button type="button" class="secondary-button" onclick="closeAssistanceModal()">Cancel</button>
                        <button type="submit" class="primary-button">Submit request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
</body>
</html>
