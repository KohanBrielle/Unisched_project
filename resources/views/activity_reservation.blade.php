<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My UNISched | Activity Center Reservation</title>
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

        .dashboard-nav-link { transition: all 180ms ease; }
        .dashboard-nav-link:hover,
        .dashboard-nav-link.active {
            background: linear-gradient(135deg, rgba(131, 82, 232, 0.95), rgba(78, 40, 146, 0.98));
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 14px 35px rgba(75, 45, 140, 0.2);
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

        .primary-button,
        .secondary-button {
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

        .primary-button:hover,
        .secondary-button:hover {
            transform: translateY(-1px);
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.15rem 0.8rem;
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

        .form-input {
            width: 100%;
            border-radius: 18px;
            border: 1px solid #e9ddff;
            background: rgba(251, 249, 255, 0.92);
            padding: 0.85rem 1rem;
            color: #0f172a;
        }

        .form-input:focus {
            outline: none;
            border-color: rgba(133, 70, 255, 0.45);
            box-shadow: 0 0 0 4px rgba(133, 70, 255, 0.1);
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
                                <li><a href="{{ route('activity.reservation') }}" class="dashboard-nav-link active flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="far fa-calendar-alt w-4"></i><span>Activity Center Reservation</span></a></li>
                                <li><a href="{{ route('library.status') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-book w-4"></i><span>Library Status</span></a></li>
                                <li><a href="{{ route('gym.status') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-dumbbell w-4"></i><span>Gym Status</span></a></li>
                                <li><a href="{{ route('canteen.status') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-utensils w-4"></i><span>Canteen Status</span></a></li>
                                <li><a href="{{ route('bao.status') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-building w-4"></i><span>BAO Status</span></a></li>
                                <li><a href="{{ route('equipment.borrowing') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-tools w-4"></i><span>Equipment Borrowing</span></a></li>
                                @if(auth()->user()->is_admin)
                                    <li><a href="{{ route('system.admin') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-cog w-4"></i><span>System Admin</span></a></li>
                                @endif
                            </ul>
                        </nav>
                        <div class="mt-4 rounded-2xl border border-violet-100 bg-violet-50/80 px-4 py-3">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Campus pulse</p>
                            <p class="mt-2 text-sm text-slate-600">Your reservation and facility pages now use the same polished console theme as the main dashboard.</p>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="flex-1">
                <div class="glass-panel rounded-[30px] border border-white/70 p-4 shadow-glow sm:p-6 lg:p-7">
                    <header class="flex flex-col gap-5 border-b border-violet-100 pb-5 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <p class="text-[0.7rem] font-bold uppercase tracking-[0.3em] text-violet-700">Reservation</p>
                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Activity Center Booking</h1>
                                <span class="dashboard-pill"><i class="fas fa-calendar-alt"></i> 2026 A.Y.</span>
                            </div>
                            <p class="mt-2 max-w-2xl text-sm text-slate-600 sm:text-base">Reserve the Activity Center and review upcoming approved bookings from a single elevated workspace.</p>
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

                    @php
                        $approved = $facility->reservations()
                            ->where('status', 'approved')
                            ->where('start_time', '>', now())
                            ->orderBy('start_time')
                            ->get();
                    @endphp

                    <section class="mt-6 grid gap-4 xl:grid-cols-[1.1fr_0.9fr]">
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Facility snapshot</p>
                                    <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $facility->room_name }}</h2>
                                    <p class="mt-1 text-sm text-slate-600">{{ $facility->building }}</p>
                                </div>
                                <span class="status-chip status-open">Open</span>
                            </div>
                            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-[20px] bg-violet-50/80 p-4">
                                    <p class="text-sm text-slate-500">Current occupancy</p>
                                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $facility->current_occupancy }}</p>
                                </div>
                                <div class="rounded-[20px] bg-violet-50/80 p-4">
                                    <p class="text-sm text-slate-500">Capacity</p>
                                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $facility->capacity }}</p>
                                </div>
                                <div class="rounded-[20px] bg-violet-50/80 p-4">
                                    <p class="text-sm text-slate-500">Bookings</p>
                                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $approved->count() }}</p>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between gap-4 rounded-[20px] bg-violet-50/80 px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Availability</p>
                                    <p class="mt-1 text-sm text-slate-600">The Activity Center is ready for a new reservation request.</p>
                                </div>
                                <div class="progress-circle" data-percent="{{ round(($facility->current_occupancy / max($facility->capacity, 1)) * 100) }}">
                                    <svg><circle cx="35" cy="35" r="30"></circle><circle cx="35" cy="35" r="30"></circle></svg>
                                    <div class="number">{{ round(($facility->current_occupancy / max($facility->capacity, 1)) * 100) }}%</div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Reserve the Activity Center</p>
                            <h2 class="mt-2 text-xl font-bold text-slate-900">Choose your time slot</h2>
                            <form id="reservation-form" class="mt-4 space-y-3">
                                <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Start Date & Time</label>
                                    <input type="datetime-local" name="start_time" required class="form-input">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">End Date & Time</label>
                                    <input type="datetime-local" name="end_time" required class="form-input">
                                </div>
                                <button type="submit" class="primary-button w-full">Submit Reservation</button>
                            </form>
                            <div id="reservation-result" class="mt-4 text-sm text-slate-600"></div>
                        </div>
                    </section>

                    <section class="mt-6 rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Upcoming approved reservations</p>
                                <h2 class="mt-2 text-lg font-bold text-slate-900">Planning ahead</h2>
                            </div>
                        </div>
                        @if($approved->isEmpty())
                            <p class="mt-4 text-sm text-slate-600">No approved bookings for this facility yet.</p>
                        @else
                            <ul class="mt-4 space-y-3">
                                @foreach($approved as $reservation)
                                    <li class="rounded-[18px] bg-violet-50/70 px-4 py-3">
                                        <p class="font-bold text-slate-900">{{ $reservation->user->name }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $reservation->start_time->format('M d, H:i') }} � {{ $reservation->end_time->format('H:i') }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                </div>
            </main>
        </div>
    </div>

    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
    <script>
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
