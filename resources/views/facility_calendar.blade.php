<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My UNISched | {{ $facility->room_name }} Calendar</title>
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

        .glass-panel { background: rgba(255, 255, 255, 0.86); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
        .dashboard-nav-link { transition: all 180ms ease; }
        .dashboard-nav-link:hover,
        .dashboard-nav-link.active {
            background: linear-gradient(135deg, rgba(131, 82, 232, 0.95), rgba(78, 40, 146, 0.98));
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 14px 35px rgba(75, 45, 140, 0.2);
        }
        .dashboard-pill { display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 999px; padding: 0.55rem 1rem; background: rgba(133, 70, 255, 0.12); color: #4d2393; font-weight: 700; }
        .calendar-day {
            min-height: 110px;
            border-radius: 18px;
            padding: 12px;
            border: 1px solid #eee8ff;
            background: rgba(251, 249, 255, 0.92);
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
        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            display: inline-block;
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
                                <li><a href="{{ route('dashboard') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-th-large w-4"></i><span>Facility Status Overview</span></a></li>
                                <li><a href="{{ route('activity.reservation') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="far fa-calendar-alt w-4"></i><span>Activity Center Reservation</span></a></li>
                                <li><a href="{{ route('library.status') }}" class="dashboard-nav-link {{ request()->routeIs('library.status') ? 'active' : '' }} flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-book w-4"></i><span>Library Status</span></a></li>
                                <li><a href="{{ route('gym.status') }}" class="dashboard-nav-link {{ request()->routeIs('gym.status') ? 'active' : '' }} flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-dumbbell w-4"></i><span>Gym Status</span></a></li>
                                <li><a href="{{ route('canteen.status') }}" class="dashboard-nav-link {{ request()->routeIs('canteen.status') ? 'active' : '' }} flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-utensils w-4"></i><span>Canteen Status</span></a></li>
                                <li><a href="{{ route('bao.status') }}" class="dashboard-nav-link {{ request()->routeIs('bao.status') ? 'active' : '' }} flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-building w-4"></i><span>BAO Status</span></a></li>
                                <li><a href="{{ route('equipment.borrowing') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-tools w-4"></i><span>Equipment Borrowing</span></a></li>
                                @if(auth()->user()->is_admin)
                                    <li><a href="{{ route('system.admin') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-cog w-4"></i><span>System Admin</span></a></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </aside>

            <main class="flex-1">
                <div class="glass-panel rounded-[30px] border border-white/70 p-4 shadow-glow sm:p-6 lg:p-7">
                    <header class="flex flex-col gap-5 border-b border-violet-100 pb-5 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <p class="text-[0.7rem] font-bold uppercase tracking-[0.3em] text-violet-700">Reservation Calendar</p>
                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">{{ $facility->room_name }}</h1>
                                <span class="dashboard-pill"><i class="fas fa-calendar-alt"></i> 2026 A.Y.</span>
                            </div>
                            <p class="mt-2 max-w-2xl text-sm text-slate-600 sm:text-base">Browse approved bookings on this month’s calendar and see which dates are already reserved.</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
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
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-700 ring-1 ring-violet-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </header>

                    <section class="mt-6 grid gap-4 xl:grid-cols-[1.2fr_0.8fr]">
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <h2 class="text-lg font-bold text-slate-900">Calendar</h2>
                                <div class="flex items-center gap-2">
                                    <button onclick="previousMonth()" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-violet-100 text-violet-700"><i class="fas fa-chevron-left"></i></button>
                                    <span id="month-label" class="text-sm font-semibold text-slate-700"></span>
                                    <button onclick="nextMonth()" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-violet-100 text-violet-700"><i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>
                            <div class="mt-4 grid grid-cols-7 gap-3">
                                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                                    <div class="rounded-[14px] bg-violet-50 px-3 py-2 text-center text-sm font-bold text-violet-700">{{ $day }}</div>
                                @endforeach
                            </div>
                            <div id="calendar" class="mt-3 grid grid-cols-7 gap-3"></div>
                            <div class="mt-4 flex flex-wrap gap-4 text-sm text-slate-600">
                                <span class="inline-flex items-center gap-2"><span class="legend-dot bg-[#d4e3ff]"></span> Available</span>
                                <span class="inline-flex items-center gap-2"><span class="legend-dot bg-[#f0c6c6]"></span> Reserved</span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                                <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Reservation Details</p>
                                <ul class="mt-4 space-y-3">
                                    @forelse($facility->reservations()->where('status', 'approved')->orderBy('start_time')->get() as $reservation)
                                        <li class="rounded-[18px] bg-violet-50/70 px-4 py-3">
                                            <p class="font-bold text-slate-900">{{ $reservation->user->name }}</p>
                                            <p class="mt-1 text-sm text-slate-600">{{ $reservation->start_time->format('M d, H:i') }} — {{ $reservation->end_time->format('H:i') }}</p>
                                        </li>
                                    @empty
                                        <li class="rounded-[18px] bg-violet-50/70 px-4 py-3 text-sm text-slate-600">No approved bookings have been added yet.</li>
                                    @endforelse
                                </ul>
                            </div>

                            <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                                <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Room Summary</p>
                                <div class="mt-4 space-y-2 text-sm text-slate-600">
                                    <p>Building: {{ $facility->building }}</p>
                                    <p>Capacity: {{ $facility->capacity }}</p>
                                    <p>Current occupancy: {{ $facility->current_occupancy }}</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    @php
        $calendarReservations = $facility->reservations()->where('status', 'approved')->get()->map(function ($r) {
            return [
                'start' => optional($r->start_time)->format('Y-m-d'),
                'end' => optional($r->end_time)->format('Y-m-d'),
                'user' => optional($r->user)->name ?? 'Unknown',
                'time' => ($r->start_time && $r->end_time) ? $r->start_time->format('H:i') . ' - ' . $r->end_time->format('H:i') : 'N/A',
            ];
        })->toArray();
    @endphp

    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
    <script>
        let currentDate = new Date();
        const reservations = @json($calendarReservations);

        const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        function generateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const calendar = document.getElementById('calendar');
            calendar.innerHTML = '';

            document.getElementById('month-label').textContent = currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

            for (let i = 0; i < firstDay.getDay(); i++) {
                const filler = document.createElement('div');
                filler.className = 'calendar-day hidden';
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
                number.className = 'text-sm font-bold text-slate-900';
                number.textContent = day;
                dayElement.appendChild(number);

                if (activeReservation) {
                    const info = document.createElement('div');
                    info.className = 'text-[0.72rem] text-slate-700';
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
