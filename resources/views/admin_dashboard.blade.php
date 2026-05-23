<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My UNISched | Admin Dashboard</title>
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
        }

        .admin-tab {
            transition: all 180ms ease;
        }

        .admin-tab.active,
        .admin-tab:hover {
            background: linear-gradient(135deg, rgba(131, 82, 232, 0.95), rgba(78, 40, 146, 0.98));
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 14px 35px rgba(75, 45, 140, 0.2);
        }

        .admin-tab-pane {
            display: none;
        }

        .admin-tab-pane.active {
            display: block;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.86);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.2rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .status-open {
            background: rgba(45, 167, 94, 0.14);
            color: #1d6e47;
        }

        .status-closed {
            background: rgba(177, 177, 177, 0.16);
            color: #4a4a4a;
        }

        .status-lunch_break {
            background: rgba(246, 203, 68, 0.2);
            color: #8c6908;
        }

        .status-reserved {
            background: rgba(236, 72, 153, 0.16);
            color: #9d1850;
        }

        .status-in_use {
            background: rgba(120, 70, 200, 0.16);
            color: #4d1d8b;
        }

        .action-btn {
            transition: all 180ms ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .create-btn {
            transition: all 180ms ease;
        }

        .create-btn:hover {
            filter: brightness(1.04);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="min-h-screen text-slate-900">
    <div class="relative min-h-screen overflow-hidden">
        <video autoplay muted loop playsinline class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-15 blur-[2px]">
            <source src="{{ asset('videos/LSPU.mp4') }}" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(14,8,27,0.88),rgba(36,20,67,0.72),rgba(25,10,48,0.82))]"></div>

        <div class="relative z-10 flex min-h-screen flex-col lg:flex-row px-4 py-4 sm:px-6 lg:px-8 xl:px-10 gap-4 lg:gap-6">
            <aside class="w-full shrink-0 lg:w-80">
                <div class="glass-panel rounded-[28px] border border-white/60 p-4 shadow-glow">
                    <div class="mb-4 flex items-center gap-3 rounded-[22px] bg-[#1e0b36] px-4 py-3 text-white shadow-lg">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10">
                            <i class="fas fa-crown text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[0.65rem] uppercase tracking-[0.3em] text-violet-100/80">My UNISched</p>
                            <p class="text-lg font-semibold">Admin Console</p>
                        </div>
                    </div>

                    <div class="rounded-[24px] bg-gradient-to-b from-violet-50 to-white p-3">
                        <p class="mb-3 text-[0.68rem] font-bold uppercase tracking-[0.28em] text-slate-500">Core Navigation</p>
                        <div class="space-y-2">
                            <button type="button" class="admin-tab active w-full rounded-2xl px-4 py-3 text-left text-sm font-semibold text-slate-700" data-target="overview">
                                <span class="flex items-center gap-3"><i class="fas fa-chart-line w-4"></i>Overview</span>
                            </button>
                            <button type="button" class="admin-tab w-full rounded-2xl px-4 py-3 text-left text-sm font-semibold text-slate-700" data-target="facilities">
                                <span class="flex items-center gap-3"><i class="fas fa-building w-4"></i>Facility Management</span>
                            </button>
                            <button type="button" class="admin-tab w-full rounded-2xl px-4 py-3 text-left text-sm font-semibold text-slate-700" data-target="reservations">
                                <span class="flex items-center gap-3"><i class="fas fa-calendar-check w-4"></i>Reservations</span>
                            </button>
                            <button type="button" class="admin-tab w-full rounded-2xl px-4 py-3 text-left text-sm font-semibold text-slate-700" data-target="logs">
                                <span class="flex items-center gap-3"><i class="fas fa-clipboard-list w-4"></i>Attendance Logs</span>
                            </button>
                            <button type="button" class="admin-tab w-full rounded-2xl px-4 py-3 text-left text-sm font-semibold text-slate-700" data-target="equipment">
                                <span class="flex items-center gap-3"><i class="fas fa-tools w-4"></i>Equipment</span>
                            </button>
                            <button type="button" class="admin-tab w-full rounded-2xl px-4 py-3 text-left text-sm font-semibold text-slate-700" data-target="users">
                                <span class="flex items-center gap-3"><i class="fas fa-users w-4"></i>User Management</span>
                            </button>
                            <button type="button" class="admin-tab w-full rounded-2xl px-4 py-3 text-left text-sm font-semibold text-slate-700" data-target="assistance">
                                <span class="flex items-center gap-3"><i class="fas fa-headset w-4"></i>Assistance Requests</span>
                            </button>
                        </div>

                        <div class="mt-4 rounded-2xl border border-violet-100 bg-violet-50/80 px-4 py-3">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Quick actions</p>
                            <p class="mt-2 text-sm text-slate-600">Move between operational views without losing context.</p>
                            <a href="{{ route('dashboard') }}" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-violet-700">
                                <i class="fas fa-arrow-left"></i> Back to user dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="flex-1">
                <div class="glass-panel rounded-[30px] border border-white/70 p-4 shadow-glow sm:p-6 lg:p-7">
                    <header class="flex flex-col gap-5 border-b border-violet-100 pb-5 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <p class="text-[0.7rem] font-bold uppercase tracking-[0.3em] text-violet-700">Admin Dashboard</p>
                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">System Admin Dashboard</h1>
                                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-sm font-bold text-emerald-700">
                                    <i class="fas fa-shield-alt"></i>Secure mode
                                </span>
                            </div>
                            <p class="mt-2 max-w-2xl text-sm text-slate-600 sm:text-base">Manage facilities, users, equipment, and assistance requests from one refined control center.</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center gap-2 rounded-full bg-violet-100 px-4 py-2 text-sm font-semibold text-violet-800">
                                <i class="fas fa-calendar-alt"></i> 2026 A.Y.
                            </span>
                            <div class="flex items-center gap-3 rounded-full bg-white/80 px-3 py-2 shadow-sm ring-1 ring-violet-100">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3">
                                    <img src="{{ auth()->user()->profile_picture_url }}" alt="Profile" class="h-10 w-10 rounded-full object-cover ring-2 ring-violet-100">
                                    <div class="text-left">
                                        <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                        <p class="text-[0.7rem] uppercase tracking-[0.2em] text-slate-500">Admin</p>
                                    </div>
                                </a>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-700 ring-1 ring-violet-100 transition hover:-translate-y-0.5 hover:ring-violet-200">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-slate-800">
                                        <i class="fas fa-right-from-bracket"></i> Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </header>

                    <section id="overview" class="admin-tab-pane active pt-6">
                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                                <p class="text-sm text-slate-500">Total Users</p>
                                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $users->count() }}</p>
                                <p class="mt-2 text-sm text-emerald-700">{{ $users->where('is_admin', true)->count() }} admin accounts</p>
                            </div>
                            <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                                <p class="text-sm text-slate-500">Facilities</p>
                                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $facilities->count() }}</p>
                                <p class="mt-2 text-sm text-violet-700">Across campus locations</p>
                            </div>
                            <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                                <p class="text-sm text-slate-500">Pending Actions</p>
                                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $pendingReservations->count() + $borrowedEquipment->count() + $assistanceRequests->where('status', 'pending')->count() }}</p>
                                <p class="mt-2 text-sm text-amber-700">Review approvals and requests</p>
                            </div>
                            <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                                <p class="text-sm text-slate-500">System Health</p>
                                <p class="mt-3 text-3xl font-bold text-slate-900">98%</p>
                                <p class="mt-2 text-sm text-emerald-700">Live operations are stable</p>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(280px,1fr)]">
                            <div class="rounded-[28px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80 sm:p-6">
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-violet-700">Reservation Calendar</p>
                                        <h2 class="mt-1 text-lg font-bold text-slate-900">Upcoming activities at a glance</h2>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="button" id="calendarPrevBtn" class="action-btn rounded-full bg-violet-100 px-4 py-2 text-sm font-semibold text-violet-800">Previous</button>
                                        <span id="calendarMonthLabel" class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">Current month</span>
                                        <button type="button" id="calendarNextBtn" class="action-btn rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Next</button>
                                    </div>
                                </div>
                                <div class="mt-5 grid grid-cols-7 gap-2 text-center text-[0.72rem] font-bold uppercase tracking-[0.18em] text-slate-500">
                                    <span>Sun</span>
                                    <span>Mon</span>
                                    <span>Tue</span>
                                    <span>Wed</span>
                                    <span>Thu</span>
                                    <span>Fri</span>
                                    <span>Sat</span>
                                </div>
                                <div id="adminCalendarGrid" class="mt-3 grid grid-cols-7 gap-3"></div>
                            </div>

                            <div class="rounded-[28px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80 sm:p-6">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-violet-700">Upcoming approvals</p>
                                        <h2 class="mt-1 text-lg font-bold text-slate-900">Priority schedule</h2>
                                    </div>
                                    <span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-bold text-violet-800">Live</span>
                                </div>
                                <ul id="calendarSummaryList" class="mt-4 space-y-3"></ul>
                            </div>
                        </div>

                        <div class="mt-6 rounded-[28px] bg-gradient-to-r from-[#2b114d] to-[#4a1d80] p-5 text-white shadow-[0_22px_60px_rgba(69,39,133,0.26)] sm:p-6">
                            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                                <div>
                                    <p class="text-sm font-semibold text-violet-100">Danger Zone</p>
                                    <h2 class="mt-2 text-xl font-bold">Data Cleanup & Audit Actions</h2>
                                    <p class="mt-3 text-sm text-violet-50/90">Use these actions carefully. They remove historical records from the database and affect reports, attendance logs, and reservation visibility.</p>
                                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                        <div class="rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/10">
                                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-100">Purge old logs</p>
                                            <p class="mt-2 text-sm text-violet-50/90">Clear stale attendance entries and keep the system tidy.</p>
                                        </div>
                                        <div class="rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/10">
                                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-100">Reset year data</p>
                                            <p class="mt-2 text-sm text-violet-50/90">Remove expired reservations to refresh the current academic cycle.</p>
                                        </div>
                                    </div>
                                </div>

                                <div id="cleanup-actions" class="rounded-[24px] bg-white/95 p-5 text-slate-900">
                                    <p class="text-sm font-semibold text-violet-700">Maintenance actions</p>
                                    <p class="mt-2 text-sm text-slate-600">Choose a destructive action and confirm before applying it.</p>
                                    <div class="mt-4 space-y-3">
                                        <button type="button" onclick="cleanupAttendanceLogs()" class="w-full rounded-2xl bg-rose-600 px-4 py-3 text-sm font-bold text-white shadow-[0_14px_40px_rgba(220,38,38,0.25)] transition hover:-translate-y-0.5 hover:bg-rose-500">
                                            <span class="flex items-center justify-center gap-2"><i class="fas fa-trash"></i> Clear Database</span>
                                        </button>
                                        <button type="button" onclick="cleanupExpiredReservations()" class="w-full rounded-2xl bg-violet-700 px-4 py-3 text-sm font-bold text-white shadow-[0_14px_40px_rgba(109,40,217,0.28)] transition hover:-translate-y-0.5 hover:bg-violet-600">
                                            <span class="flex items-center justify-center gap-2"><i class="fas fa-calendar-times"></i> Reset Academic Year Reservations</span>
                                        </button>
                                    </div>
                                    <p class="mt-4 text-xs text-slate-500">These buttons are connected to the existing cleanup endpoints and will refresh the page after completion.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="facilities" class="admin-tab-pane pt-6">
                        <div class="rounded-[28px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80 sm:p-6">
                            <div class="flex flex-wrap items-end justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-violet-700">Facility Management</p>
                                    <h2 class="mt-1 text-lg font-bold text-slate-900">Create and update campus facilities</h2>
                                </div>
                                <p class="max-w-2xl text-sm text-slate-600">Set operating hours, lunch breaks, and borrowing availability in one streamlined form.</p>
                            </div>

                            <form id="createFacilityForm" class="mt-5 grid gap-4 lg:grid-cols-2">
                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Room Name</span>
                                    <input type="text" id="room_name" name="room_name" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100" required>
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Building</span>
                                    <input type="text" id="building" name="building" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100" required>
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Capacity</span>
                                    <input type="number" id="capacity" name="capacity" min="1" value="30" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100" required>
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Current Occupancy</span>
                                    <input type="number" id="current_occupancy" name="current_occupancy" min="0" value="0" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100" required>
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Opening Time</span>
                                    <input type="time" id="opening_time" name="opening_time" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                                    <span class="mt-1 block text-xs text-slate-500">Optional</span>
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Closing Time</span>
                                    <input type="time" id="closing_time" name="closing_time" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                                    <span class="mt-1 block text-xs text-slate-500">Optional</span>
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Lunch Start</span>
                                    <input type="time" id="lunch_start" name="lunch_start" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                                    <span class="mt-1 block text-xs text-slate-500">Optional</span>
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Lunch End</span>
                                    <input type="time" id="lunch_end" name="lunch_end" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                                    <span class="mt-1 block text-xs text-slate-500">Optional</span>
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Lunch Mode</span>
                                    <select id="lunch_mode" name="lunch_mode" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                                        <option value="scheduled">Scheduled</option>
                                        <option value="disabled">Disabled</option>
                                    </select>
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Borrowable</span>
                                    <select id="is_borrowable" name="is_borrowable" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </label>
                                <div class="lg:col-span-2 flex justify-end">
                                    <button type="submit" class="create-btn rounded-full bg-violet-700 px-5 py-3 text-sm font-bold text-white shadow-[0_14px_40px_rgba(109,40,217,0.28)]">Create Facility</button>
                                </div>
                            </form>
                        </div>

                        <div class="mt-6 rounded-[28px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80 sm:p-6">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-violet-700">Facility List</p>
                                    <h2 class="mt-1 text-lg font-bold text-slate-900">Live campus inventory</h2>
                                </div>
                                <p class="text-sm text-slate-600">Browse details and manage room statuses in place.</p>
                            </div>
                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-violet-100">
                                    <thead>
                                        <tr class="text-left text-sm text-slate-500">
                                            <th class="px-3 py-3 font-semibold">Room</th>
                                            <th class="px-3 py-3 font-semibold">Building</th>
                                            <th class="px-3 py-3 font-semibold">Capacity</th>
                                            <th class="px-3 py-3 font-semibold">Occupancy</th>
                                            <th class="px-3 py-3 font-semibold">Status</th>
                                            <th class="px-3 py-3 font-semibold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-violet-100 text-sm text-slate-700">
                                        @forelse($facilities as $facility)
                                            <tr>
                                                <td class="px-3 py-3 font-semibold text-slate-900">{{ $facility->room_name }}</td>
                                                <td class="px-3 py-3">{{ $facility->building }}</td>
                                                <td class="px-3 py-3">{{ $facility->capacity }}</td>
                                                <td class="px-3 py-3">{{ $facility->current_occupancy }}/{{ $facility->capacity }}</td>
                                                <td class="px-3 py-3"><span class="status-badge status-{{ $facility->computed_status }}">{{ $facility->status_label }}</span></td>
                                                <td class="px-3 py-3">
                                                    <div class="flex flex-wrap gap-2">
                                                        <button type="button" class="action-btn rounded-full bg-violet-100 px-3 py-1.5 text-xs font-bold text-violet-800" onclick="openEditFacilityModal(this)"
                                                            data-facility-id="{{ $facility->id }}"
                                                            data-room-name="{{ $facility->room_name }}"
                                                            data-building="{{ $facility->building }}"
                                                            data-capacity="{{ $facility->capacity }}"
                                                            data-current-occupancy="{{ $facility->current_occupancy }}"
                                                            data-opening-time="{{ is_string($facility->opening_time) ? substr($facility->opening_time, 0, 5) : '' }}"
                                                            data-closing-time="{{ is_string($facility->closing_time) ? substr($facility->closing_time, 0, 5) : '' }}"
                                                            data-lunch-start="{{ is_string($facility->lunch_start) ? substr($facility->lunch_start, 0, 5) : '' }}"
                                                            data-lunch-end="{{ is_string($facility->lunch_end) ? substr($facility->lunch_end, 0, 5) : '' }}"
                                                            data-lunch-mode="{{ $facility->lunch_mode ?? 'scheduled' }}"
                                                            data-status="{{ $facility->computed_status }}">
                                                            Edit
                                                        </button>
                                                        <button type="button" class="action-btn rounded-full bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700" onclick="deleteFacility({{ $facility->id }})">Delete</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-3 py-6 text-center text-slate-500">No facilities have been created yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                    <section id="reservations" class="admin-tab-pane pt-6">
                        <div class="grid gap-6 xl:grid-cols-2">
                            <div class="rounded-[28px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80 sm:p-6">
                                <p class="text-sm font-semibold text-violet-700">Pending Reservations</p>
                                <h2 class="mt-1 text-lg font-bold text-slate-900">Approve or reject new booking requests</h2>
                                <div class="mt-4 overflow-x-auto">
                                    <table class="min-w-full divide-y divide-violet-100">
                                        <thead>
                                            <tr class="text-left text-sm text-slate-500">
                                                <th class="px-3 py-3 font-semibold">User</th>
                                                <th class="px-3 py-3 font-semibold">Facility</th>
                                                <th class="px-3 py-3 font-semibold">Schedule</th>
                                                <th class="px-3 py-3 font-semibold">Requested</th>
                                                <th class="px-3 py-3 font-semibold">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-violet-100 text-sm text-slate-700">
                                            @forelse($pendingReservations as $reservation)
                                                <tr>
                                                    <td class="px-3 py-3">{{ $reservation->user->name ?? 'Unknown user' }}</td>
                                                    <td class="px-3 py-3">{{ $reservation->facility->room_name ?? 'Unknown facility' }}</td>
                                                    <td class="px-3 py-3">{{ optional($reservation->start_time)->format('M d, Y H:i') }} - {{ optional($reservation->end_time)->format('H:i') }}</td>
                                                    <td class="px-3 py-3">{{ optional($reservation->created_at)->diffForHumans() ?? 'Just now' }}</td>
                                                    <td class="px-3 py-3">
                                                        <div class="flex flex-wrap gap-2">
                                                            <button type="button" onclick="approveReservation({{ $reservation->id }})" class="action-btn rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-700">Accept</button>
                                                            <button type="button" onclick="rejectReservation({{ $reservation->id }})" class="action-btn rounded-full bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700">Reject</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">No pending reservations need attention.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="rounded-[28px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80 sm:p-6">
                                <p class="text-sm font-semibold text-violet-700">Approved Reservations</p>
                                <h2 class="mt-1 text-lg font-bold text-slate-900">Current approved bookings</h2>
                                <div class="mt-4 overflow-x-auto">
                                    <table class="min-w-full divide-y divide-violet-100">
                                        <thead>
                                            <tr class="text-left text-sm text-slate-500">
                                                <th class="px-3 py-3 font-semibold">User</th>
                                                <th class="px-3 py-3 font-semibold">Facility</th>
                                                <th class="px-3 py-3 font-semibold">Schedule</th>
                                                <th class="px-3 py-3 font-semibold">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-violet-100 text-sm text-slate-700">
                                            @forelse($approvedReservations as $reservation)
                                                <tr>
                                                    <td class="px-3 py-3">{{ $reservation->user->name ?? 'Unknown user' }}</td>
                                                    <td class="px-3 py-3">{{ $reservation->facility->room_name ?? 'Unknown facility' }}</td>
                                                    <td class="px-3 py-3">{{ optional($reservation->start_time)->format('M d, Y H:i') }} - {{ optional($reservation->end_time)->format('H:i') }}</td>
                                                    <td class="px-3 py-3"><span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">Approved</span></td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4" class="px-3 py-6 text-center text-slate-500">No approved reservations are active right now.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="logs" class="admin-tab-pane pt-6">
                        <div class="rounded-[28px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80 sm:p-6">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-violet-700">Attendance Logs</p>
                                    <h2 class="mt-1 text-lg font-bold text-slate-900">Per-facility activity history</h2>
                                </div>
                                <button type="button" onclick="cleanupAttendanceLogs()" class="action-btn rounded-full bg-rose-600 px-4 py-2 text-sm font-bold text-white shadow-[0_14px_40px_rgba(220,38,38,0.25)]">Clear Attendance Logs</button>
                            </div>

                            <div class="mt-4 flex flex-wrap items-center gap-3">
                                <label for="logFacilityFilter" class="text-sm font-semibold text-slate-700">Filter facility</label>
                                <select id="logFacilityFilter" class="rounded-2xl border border-violet-100 bg-white px-4 py-2 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                                    <option value="all">All facilities</option>
                                    @foreach($facilities as $facility)
                                        <option value="{{ $facility->id }}">{{ $facility->room_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @php
                                $logsByFacility = $attendanceLogs->groupBy('facility_id');
                            @endphp

                            <div class="mt-5 space-y-4">
                                @forelse($facilities as $facility)
                                    @php
                                        $facilityLogs = $logsByFacility->get($facility->id, collect());
                                    @endphp
                                    <div class="attendance-log-group rounded-[24px] border border-violet-100 bg-slate-50/70 p-4" data-facility-id="{{ $facility->id }}">
                                        <div class="flex flex-wrap items-center justify-between gap-4">
                                            <div>
                                                <p class="text-sm font-semibold text-violet-700">{{ $facility->room_name }}</p>
                                                <p class="text-sm text-slate-500">{{ $facility->building }}</p>
                                            </div>
                                            <span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-bold text-violet-800">{{ $facilityLogs->count() }} records</span>
                                        </div>

                                        @if($facilityLogs->isEmpty())
                                            <p class="mt-4 text-sm text-slate-500">No attendance records are available for this facility yet.</p>
                                        @else
                                            @php
                                                $groupedByDay = $facilityLogs->groupBy(fn ($log) => optional($log->time_in)->format('Y-m-d'));
                                            @endphp
                                            <div class="mt-4 space-y-3">
                                                @foreach($groupedByDay as $day => $logsForDay)
                                                    <div class="rounded-[20px] bg-white px-4 py-3 ring-1 ring-violet-100">
                                                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">{{ \Illuminate\Support\Carbon::parse($day)->format('M d, Y') }}</p>
                                                        <div class="mt-3 space-y-2">
                                                            @foreach($logsForDay->sortByDesc('time_in') as $log)
                                                                <div class="flex flex-wrap items-center justify-between gap-4 rounded-[18px] bg-slate-50 px-3 py-2">
                                                                    <div>
                                                                        <p class="font-semibold text-slate-900">{{ $log->user->name ?? 'Unknown user' }}</p>
                                                                        <p class="text-sm text-slate-500">Checked in at {{ optional($log->time_in)->format('H:i') }}</p>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <p class="text-sm font-semibold text-slate-700">@if($log->time_out) Checked out at {{ optional($log->time_out)->format('H:i') }} @else Still in the facility @endif</p>
                                                                        <p class="text-xs text-slate-500">{{ optional($log->time_in)->diffForHumans() ?? 'Recently' }}</p>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No facilities are currently configured.</p>
                                @endforelse
                            </div>
                        </div>
                    </section>

                    <section id="equipment" class="admin-tab-pane pt-6">
                        <div class="grid gap-6 xl:grid-cols-2">
                            <div class="rounded-[28px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80 sm:p-6">
                                <p class="text-sm font-semibold text-violet-700">Borrowed Equipment</p>
                                <h2 class="mt-1 text-lg font-bold text-slate-900">Current equipment inventory</h2>
                                <div class="mt-4 overflow-x-auto">
                                    <table class="min-w-full divide-y divide-violet-100">
                                        <thead>
                                            <tr class="text-left text-sm text-slate-500">
                                                <th class="px-3 py-3 font-semibold">User</th>
                                                <th class="px-3 py-3 font-semibold">Equipment</th>
                                                <th class="px-3 py-3 font-semibold">Borrowed At</th>
                                                <th class="px-3 py-3 font-semibold">Return Date</th>
                                                <th class="px-3 py-3 font-semibold">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-violet-100 text-sm text-slate-700">
                                            @forelse($borrowedEquipment as $item)
                                                <tr>
                                                    <td class="px-3 py-3">{{ $item->user->name }}</td>
                                                    <td class="px-3 py-3">{{ $item->equipment_name }}</td>
                                                    <td class="px-3 py-3">{{ optional($item->borrowed_at)->format('M d, Y H:i') ?? 'Pending approval' }}</td>
                                                    <td class="px-3 py-3">{{ $item->return_date->format('M d, Y') }}</td>
                                                    <td class="px-3 py-3">{{ ucfirst($item->status) }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">No equipment is currently borrowed.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="rounded-[28px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80 sm:p-6">
                                <p class="text-sm font-semibold text-violet-700">Pending actions</p>
                                <h2 class="mt-1 text-lg font-bold text-slate-900">Borrow approvals and returns</h2>
                                <div class="mt-4 space-y-4">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-violet-100">
                                            <thead>
                                                <tr class="text-left text-sm text-slate-500">
                                                    <th class="px-3 py-3 font-semibold">User</th>
                                                    <th class="px-3 py-3 font-semibold">Equipment</th>
                                                    <th class="px-3 py-3 font-semibold">Requested Return</th>
                                                    <th class="px-3 py-3 font-semibold">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-violet-100 text-sm text-slate-700">
                                                @forelse($pendingBorrowRequests as $item)
                                                    <tr>
                                                        <td class="px-3 py-3">{{ $item->user->name }}</td>
                                                        <td class="px-3 py-3">{{ $item->equipment_name }}</td>
                                                        <td class="px-3 py-3">{{ $item->return_date->format('M d, Y') }}</td>
                                                        <td class="px-3 py-3"><button type="button" onclick="approveBorrowing({{ $item->id }})" class="action-btn rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-700">Approve</button></td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="4" class="px-3 py-6 text-center text-slate-500">No pending borrow requests.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-violet-100">
                                            <thead>
                                                <tr class="text-left text-sm text-slate-500">
                                                    <th class="px-3 py-3 font-semibold">User</th>
                                                    <th class="px-3 py-3 font-semibold">Equipment</th>
                                                    <th class="px-3 py-3 font-semibold">Borrowed At</th>
                                                    <th class="px-3 py-3 font-semibold">Return Date</th>
                                                    <th class="px-3 py-3 font-semibold">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-violet-100 text-sm text-slate-700">
                                                @forelse($returnRequests as $item)
                                                    <tr>
                                                        <td class="px-3 py-3">{{ $item->user->name }}</td>
                                                        <td class="px-3 py-3">{{ $item->equipment_name }}</td>
                                                        <td class="px-3 py-3">{{ optional($item->borrowed_at)->format('M d, Y H:i') ?? 'N/A' }}</td>
                                                        <td class="px-3 py-3">{{ $item->return_date->format('M d, Y') }}</td>
                                                        <td class="px-3 py-3"><button type="button" onclick="approveReturnRequest({{ $item->id }})" class="action-btn rounded-full bg-violet-100 px-3 py-1.5 text-xs font-bold text-violet-800">Approve Return</button></td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">No return requests are pending.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="users" class="admin-tab-pane pt-6">
                        <div class="rounded-[28px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80 sm:p-6">
                            <p class="text-sm font-semibold text-violet-700">User Management</p>
                            <h2 class="mt-1 text-lg font-bold text-slate-900">Account controls and permissions</h2>
                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-violet-100">
                                    <thead>
                                        <tr class="text-left text-sm text-slate-500">
                                            <th class="px-3 py-3 font-semibold">Name</th>
                                            <th class="px-3 py-3 font-semibold">Student ID</th>
                                            <th class="px-3 py-3 font-semibold">Email</th>
                                            <th class="px-3 py-3 font-semibold">Status</th>
                                            <th class="px-3 py-3 font-semibold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-violet-100 text-sm text-slate-700">
                                        @forelse($users as $user)
                                            <tr>
                                                <td class="px-3 py-3">{{ $user->name }}</td>
                                                <td class="px-3 py-3">{{ $user->student_id }}</td>
                                                <td class="px-3 py-3">{{ $user->email }}</td>
                                                <td class="px-3 py-3">{{ $user->is_admin ? 'Admin' : 'User' }}</td>
                                                <td class="px-3 py-3">
                                                    <div class="flex flex-wrap gap-2">
                                                        @if(! $user->is_admin)
                                                            <button type="button" onclick="makeAdmin({{ $user->id }})" class="action-btn rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-700">Make Admin</button>
                                                        @else
                                                            <button type="button" onclick="removeAdmin({{ $user->id }})" class="action-btn rounded-full bg-amber-100 px-3 py-1.5 text-xs font-bold text-amber-700">Remove Admin</button>
                                                        @endif
                                                        <button type="button" onclick="deleteUser({{ $user->id }})" class="action-btn rounded-full bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700">Delete</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">No users are available.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                    <section id="assistance" class="admin-tab-pane pt-6">
                        <div class="rounded-[28px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80 sm:p-6">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-violet-700">Facility Assistance Requests</p>
                                    <h2 class="mt-1 text-lg font-bold text-slate-900">Facility assistance request history</h2>
                                    <p class="mt-2 text-sm text-slate-500">Review support requests by facility and date, and resolve pending issues quickly.</p>
                                </div>
                                <div class="rounded-[24px] bg-violet-50 px-4 py-3 text-sm text-violet-900">
                                    <p class="font-semibold">{{ $assistanceRequests->where('status', 'pending')->count() }} pending</p>
                                    <p class="text-violet-700">{{ $assistanceRequests->where('status', 'resolved')->count() }} resolved</p>
                                </div>
                            </div>

                            @php
                                $assistanceRequestsByFacility = $assistanceRequests->groupBy('facility_id');
                            @endphp

                            <div class="mt-5 space-y-4">
                                @forelse($facilities as $facility)
                                    @php
                                        $facilityRequests = $assistanceRequestsByFacility->get($facility->id, collect());
                                        $groupedByDay = $facilityRequests->groupBy(fn ($request) => optional($request->created_at)->format('Y-m-d'));
                                    @endphp

                                    <div class="rounded-[24px] border border-violet-100 bg-slate-50/80 p-4">
                                        <div class="flex flex-wrap items-center justify-between gap-4">
                                            <div>
                                                <p class="text-sm font-semibold text-violet-700">{{ $facility->room_name }}</p>
                                                <p class="text-sm text-slate-500">{{ $facility->building }}</p>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">{{ $facilityRequests->where('status', 'pending')->count() }} pending</span>
                                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">{{ $facilityRequests->where('status', 'resolved')->count() }} resolved</span>
                                            </div>
                                        </div>

                                        @if($facilityRequests->isEmpty())
                                            <p class="mt-4 text-sm text-slate-500">No assistance requests have been submitted for this facility yet.</p>
                                        @else
                                            <div class="mt-4 space-y-3">
                                                @foreach($groupedByDay as $day => $requestsForDay)
                                                    <div class="rounded-[20px] bg-white px-4 py-3 ring-1 ring-violet-100">
                                                        <div class="flex flex-wrap items-center justify-between gap-3">
                                                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">{{ \Illuminate\Support\Carbon::parse($day)->format('M d, Y') }}</p>
                                                            <p class="text-sm text-slate-500">{{ $requestsForDay->count() }} request{{ $requestsForDay->count() === 1 ? '' : 's' }}</p>
                                                        </div>
                                                        <div class="mt-3 space-y-2">
                                                            @foreach($requestsForDay->sortByDesc('created_at') as $request)
                                                                <div class="flex flex-wrap items-start justify-between gap-4 rounded-[18px] bg-slate-50 px-3 py-3">
                                                                    <div class="min-w-[180px]">
                                                                        <p class="font-semibold text-slate-900">{{ $request->user->name ?? 'Unknown user' }}</p>
                                                                        <p class="text-sm text-slate-500">{{ optional($request->created_at)->format('H:i') }} • {{ optional($request->created_at)->diffForHumans() }}</p>
                                                                    </div>
                                                                    <div class="flex-1">
                                                                        <p class="text-sm leading-6 text-slate-700">{{ $request->message }}</p>
                                                                    </div>
                                                                    <div class="flex flex-col items-end gap-2">
                                                                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $request->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">{{ ucfirst($request->status) }}</span>
                                                                        @if($request->status !== 'resolved')
                                                                            <button type="button" onclick="resolveAssistance({{ $request->id }})" class="action-btn rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-700">Resolve</button>
                                                                        @else
                                                                            <button type="button" onclick="clearResolvedAssistance({{ $request->id }})" class="action-btn rounded-full bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700">Clear</button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="rounded-[24px] border border-dashed border-violet-100 bg-white px-4 py-6 text-center">
                                        <p class="text-sm text-slate-500">No assistance requests have been submitted.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <div id="editFacilityModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4 py-6">
        <div class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-[28px] bg-white p-5 shadow-[0_30px_80px_rgba(22,16,35,0.3)] sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-violet-700">Edit Facility</p>
                    <h2 class="mt-1 text-xl font-bold text-slate-900">Update room details</h2>
                </div>
                <button type="button" onclick="closeEditFacilityModal()" class="text-2xl text-slate-400 transition hover:text-slate-700">&times;</button>
            </div>

            <form id="editFacilityForm" class="mt-5 grid gap-4 lg:grid-cols-2">
                <input type="hidden" id="editFacilityId">
                <label class="block">
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Room Name</span>
                    <input type="text" id="editRoomName" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100" required>
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Building</span>
                    <input type="text" id="editBuilding" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100" required>
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Capacity</span>
                    <input type="number" id="editCapacity" min="1" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100" required>
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Current Occupancy</span>
                    <input type="number" id="editCurrentOccupancy" min="0" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100" required>
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Opening Time</span>
                    <input type="time" id="editOpeningTime" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Closing Time</span>
                    <input type="time" id="editClosingTime" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Lunch Start</span>
                    <input type="time" id="editLunchStart" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Lunch End</span>
                    <input type="time" id="editLunchEnd" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Lunch Mode</span>
                    <select id="editLunchMode" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                        <option value="scheduled">Scheduled</option>
                        <option value="disabled">Disabled</option>
                    </select>
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-semibold text-slate-700">Manual Status Override</span>
                    <select id="editStatus" class="w-full rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-300 focus:ring-2 focus:ring-violet-100">
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                        <option value="reserved">Reserved</option>
                    </select>
                </label>

                <div class="lg:col-span-2 flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeEditFacilityModal()" class="rounded-full bg-slate-100 px-4 py-2 text-sm font-bold text-slate-700">Cancel</button>
                    <button type="submit" class="create-btn rounded-full bg-violet-700 px-4 py-2 text-sm font-bold text-white">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
    <script>
        const tabs = document.querySelectorAll('.admin-tab');
        const panes = document.querySelectorAll('.admin-tab-pane');
        const activeTabStorageKey = 'admin-console-active-tab';

        function activateTab(targetId) {
            const targetTab = Array.from(tabs).find((item) => item.dataset.target === targetId);
            const targetPane = document.getElementById(targetId);

            if (!targetTab || !targetPane) {
                return;
            }

            tabs.forEach((item) => item.classList.remove('active'));
            panes.forEach((pane) => pane.classList.remove('active'));

            targetTab.classList.add('active');
            targetPane.classList.add('active');
            sessionStorage.setItem(activeTabStorageKey, targetId);
        }

        function restoreActiveTab() {
            const savedTab = sessionStorage.getItem(activeTabStorageKey);

            if (savedTab && document.getElementById(savedTab)) {
                activateTab(savedTab);
                return;
            }

            const defaultTab = document.querySelector('.admin-tab.active')?.dataset.target;
            if (defaultTab) {
                sessionStorage.setItem(activeTabStorageKey, defaultTab);
            }
        }

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                activateTab(tab.dataset.target);
            });
        });

        restoreActiveTab();

        function jsonHeaders() {
            return {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
        }

        async function apiRequest(url, options = {}) {
            const response = await fetch(url, {
                headers: options.body instanceof FormData ? undefined : jsonHeaders(),
                ...options
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(data.error || data.message || 'Request failed');
            }

            return data;
        }

        async function reloadAfterAction(message) {
            const activeTab = document.querySelector('.admin-tab.active')?.dataset.target;
            if (activeTab) {
                sessionStorage.setItem(activeTabStorageKey, activeTab);
            }

            alert(message);
            window.location.reload();
        }

        async function cleanupAttendanceLogs() {
            if (!confirm('Purge all attendance logs? This action cannot be undone.')) {
                return;
            }

            try {
                const data = await apiRequest('{{ route('attendance.cleanup') }}', { method: 'POST' });
                await reloadAfterAction(data.message || 'Attendance logs cleared successfully.');
            } catch (error) {
                alert(error.message);
            }
        }

        async function cleanupExpiredReservations() {
            if (!confirm('Remove expired reservations for the academic year?')) {
                return;
            }

            try {
                const data = await apiRequest('{{ route('reservations.cleanup') }}', { method: 'POST' });
                await reloadAfterAction(data.message || 'Expired reservations were cleaned successfully.');
            } catch (error) {
                alert(error.message);
            }
        }

        async function approveReservation(reservationId) {
            try {
                const data = await apiRequest('{{ route('admin.reservations.approve', ['id' => '__ID__']) }}'.replace('__ID__', reservationId), { method: 'POST' });
                await reloadAfterAction(data.message || 'Reservation approved.');
            } catch (error) {
                alert(error.message);
            }
        }

        async function rejectReservation(reservationId) {
            try {
                const data = await apiRequest('{{ route('admin.reservations.reject', ['id' => '__ID__']) }}'.replace('__ID__', reservationId), { method: 'POST' });
                await reloadAfterAction(data.message || 'Reservation rejected.');
            } catch (error) {
                alert(error.message);
            }
        }

        async function deleteFacility(facilityId) {
            if (!confirm('Delete this facility? This cannot be undone.')) {
                return;
            }

            try {
                const data = await apiRequest('/admin/facilities/' + facilityId, { method: 'DELETE' });
                await reloadAfterAction(data.message || 'Facility deleted successfully.');
            } catch (error) {
                alert(error.message);
            }
        }

        async function resolveAssistance(assistanceId) {
            try {
                const data = await apiRequest('{{ route('admin.assistance.resolve', ['id' => '__ID__']) }}'.replace('__ID__', assistanceId), { method: 'POST' });
                await reloadAfterAction(data.message || 'Assistance request resolved.');
            } catch (error) {
                alert(error.message);
            }
        }

        async function clearResolvedAssistance(assistanceId) {
            if (!confirm('Remove this resolved assistance request from the history?')) {
                return;
            }

            try {
                const data = await apiRequest('{{ route('admin.assistance.clear', ['id' => '__ID__']) }}'.replace('__ID__', assistanceId), { method: 'DELETE' });
                await reloadAfterAction(data.message || 'Resolved assistance request cleared.');
            } catch (error) {
                alert(error.message);
            }
        }

        async function approveBorrowing(borrowingId) {
            try {
                const data = await apiRequest('{{ route('admin.borrowings.approve', ['id' => '__ID__']) }}'.replace('__ID__', borrowingId), { method: 'POST' });
                await reloadAfterAction(data.message || 'Borrow request approved.');
            } catch (error) {
                alert(error.message);
            }
        }

        async function approveReturnRequest(borrowingId) {
            try {
                const data = await apiRequest('/admin/borrowings/' + borrowingId + '/approve-return', { method: 'POST' });
                await reloadAfterAction(data.message || 'Return request approved.');
            } catch (error) {
                alert(error.message);
            }
        }

        async function makeAdmin(userId) {
            try {
                const data = await apiRequest('/admin/users/' + userId + '/make-admin', { method: 'POST' });
                await reloadAfterAction(data.message || 'User promoted to admin.');
            } catch (error) {
                alert(error.message);
            }
        }

        async function removeAdmin(userId) {
            try {
                const data = await apiRequest('/admin/users/' + userId + '/remove-admin', { method: 'POST' });
                await reloadAfterAction(data.message || 'Admin privileges removed.');
            } catch (error) {
                alert(error.message);
            }
        }

        async function deleteUser(userId) {
            if (!confirm('Delete this user account? This cannot be undone.')) {
                return;
            }

            try {
                const data = await apiRequest('/admin/users/' + userId, { method: 'DELETE' });
                await reloadAfterAction(data.message || 'User deleted successfully.');
            } catch (error) {
                alert(error.message);
            }
        }

        @php
            $calendarReservations = $approvedReservations->merge($pendingReservations)->map(function ($reservation) {
                return [
                    'start' => optional($reservation->start_time)->format('Y-m-d'),
                    'end' => optional($reservation->end_time)->format('Y-m-d'),
                    'facility' => optional($reservation->facility)->room_name ?? 'Unknown facility',
                    'user' => optional($reservation->user)->name ?? 'Unknown user',
                    'time' => optional($reservation->start_time)->format('H:i') . ' - ' . optional($reservation->end_time)->format('H:i'),
                    'status' => $reservation->status,
                    'status_label' => ucfirst($reservation->status),
                ];
            })->sortBy('start')->values();
        @endphp

        const calendarReservations = @json($calendarReservations);
        const calendarGrid = document.getElementById('adminCalendarGrid');
        const calendarSummaryList = document.getElementById('calendarSummaryList');
        const calendarMonthLabel = document.getElementById('calendarMonthLabel');
        const calendarPrevBtn = document.getElementById('calendarPrevBtn');
        const calendarNextBtn = document.getElementById('calendarNextBtn');
        let currentCalendarDate = new Date();

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

        function renderCalendar() {
            const year = currentCalendarDate.getFullYear();
            const month = currentCalendarDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const monthKey = getMonthKey(currentCalendarDate);
            const monthReservations = (calendarReservations || []).filter((reservation) => reservation.start?.slice(0, 7) === monthKey);

            calendarMonthLabel.textContent = formatMonth(currentCalendarDate);
            calendarGrid.innerHTML = '';

            for (let i = 0; i < firstDay.getDay(); i++) {
                const spacer = document.createElement('div');
                spacer.className = 'h-28 rounded-2xl bg-white/25';
                calendarGrid.appendChild(spacer);
            }

            for (let day = 1; day <= lastDay.getDate(); day++) {
                const date = new Date(year, month, day);
                const dateString = getLocalDateString(date);
                const matchingReservations = monthReservations.filter((reservation) => isReservedForDate(reservation, dateString));
                const cell = document.createElement('div');
                const hasReservation = matchingReservations.length > 0;
                const status = hasReservation ? 'Reserved' : 'Open';
                const statusTone = hasReservation ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700';
                cell.className = `rounded-[22px] border p-3 min-h-[130px] flex flex-col justify-between transition duration-200 hover:-translate-y-0.5 ${hasReservation ? 'border-rose-100 bg-gradient-to-br from-rose-50 to-white' : 'border-violet-100 bg-gradient-to-br from-white to-violet-50'}`;
                cell.innerHTML = `
                    <div class="flex items-start justify-between gap-3">
                        <span class="text-sm font-bold text-slate-900">${day}</span>
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[0.68rem] font-bold ${statusTone}">${status}</span>
                    </div>
                    <div class="mt-3 space-y-1">
                        ${hasReservation ? `<p class="text-[0.72rem] font-semibold text-slate-700">${matchingReservations.length} booking${matchingReservations.length > 1 ? 's' : ''}</p>` : `<p class="text-[0.72rem] text-slate-500">Open for walk-ins</p>`}
                        ${hasReservation ? `<p class="text-[0.7rem] text-slate-500">${matchingReservations[0].facility}</p>` : '<p class="text-[0.7rem] text-slate-500">Clean calendar slot</p>'}
                    </div>
                `;
                calendarGrid.appendChild(cell);
            }

            const todayKey = getLocalDateString(new Date());
            const summaryItems = monthReservations
                .filter((reservation) => reservation.start >= todayKey)
                .sort((a, b) => a.start.localeCompare(b.start))
                .slice(0, 5);

            if (!summaryItems.length) {
                calendarSummaryList.innerHTML = '<li class="rounded-2xl bg-white px-4 py-3 text-sm text-slate-500 ring-1 ring-violet-100">No reservations are scheduled for this month.</li>';
                return;
            }

            calendarSummaryList.innerHTML = summaryItems
                .map((reservation) => `
                    <li class="rounded-2xl bg-white px-4 py-3 ring-1 ring-violet-100">
                        <p class="text-sm font-semibold text-slate-900">${reservation.facility}</p>
                        <p class="mt-1 text-sm text-slate-600">${reservation.user}</p>
                        <p class="mt-2 text-xs font-bold uppercase tracking-[0.16em] text-violet-700">${reservation.status_label}</p>
                        <p class="mt-1 text-sm text-slate-500">${reservation.start} • ${reservation.time}</p>
                    </li>
                `)
                .join('');
        }

        calendarPrevBtn?.addEventListener('click', () => {
            currentCalendarDate = new Date(currentCalendarDate.getFullYear(), currentCalendarDate.getMonth() - 1, 1);
            renderCalendar();
        });

        calendarNextBtn?.addEventListener('click', () => {
            currentCalendarDate = new Date(currentCalendarDate.getFullYear(), currentCalendarDate.getMonth() + 1, 1);
            renderCalendar();
        });

        renderCalendar();

        const logFacilityFilter = document.getElementById('logFacilityFilter');
        const logGroups = document.querySelectorAll('.attendance-log-group');

        function applyLogFilter() {
            const selectedFacility = logFacilityFilter?.value || 'all';

            logGroups.forEach((group) => {
                const matches = selectedFacility === 'all' || group.dataset.facilityId === selectedFacility;
                group.style.display = matches ? '' : 'none';
            });
        }

        logFacilityFilter?.addEventListener('change', applyLogFilter);
        applyLogFilter();

        document.getElementById('createFacilityForm').addEventListener('submit', async function (event) {
            event.preventDefault();

            const payload = new FormData(this);
            try {
                const data = await apiRequest('{{ route('admin.facilities.store') }}', { method: 'POST', body: payload });
                alert(data.message || 'Facility created successfully');
                window.location.reload();
            } catch (error) {
                alert(error.message);
            }
        });

        let currentEditFacilityId = null;

        function openEditFacilityModal(button) {
            currentEditFacilityId = button.dataset.facilityId;
            document.getElementById('editFacilityId').value = currentEditFacilityId;
            document.getElementById('editRoomName').value = button.dataset.roomName || '';
            document.getElementById('editBuilding').value = button.dataset.building || '';
            document.getElementById('editCapacity').value = button.dataset.capacity || '';
            document.getElementById('editCurrentOccupancy').value = button.dataset.currentOccupancy || '';
            document.getElementById('editOpeningTime').value = button.dataset.openingTime || '';
            document.getElementById('editClosingTime').value = button.dataset.closingTime || '';
            document.getElementById('editLunchStart').value = button.dataset.lunchStart || '';
            document.getElementById('editLunchEnd').value = button.dataset.lunchEnd || '';
            document.getElementById('editLunchMode').value = button.dataset.lunchMode || 'scheduled';
            document.getElementById('editStatus').value = button.dataset.status || 'open';
            document.getElementById('editFacilityModal').classList.remove('hidden');
            document.getElementById('editFacilityModal').classList.add('flex');
        }

        function closeEditFacilityModal() {
            const modal = document.getElementById('editFacilityModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            currentEditFacilityId = null;
        }

        document.getElementById('editFacilityForm').addEventListener('submit', async function (event) {
            event.preventDefault();

            if (!currentEditFacilityId) {
                return;
            }

            const payload = {
                room_name: document.getElementById('editRoomName').value,
                building: document.getElementById('editBuilding').value,
                capacity: parseInt(document.getElementById('editCapacity').value, 10),
                current_occupancy: parseInt(document.getElementById('editCurrentOccupancy').value, 10),
                opening_time: document.getElementById('editOpeningTime').value,
                closing_time: document.getElementById('editClosingTime').value,
                lunch_start: document.getElementById('editLunchStart').value,
                lunch_end: document.getElementById('editLunchEnd').value,
                lunch_mode: document.getElementById('editLunchMode').value,
                status: document.getElementById('editStatus').value
            };

            try {
                const data = await apiRequest('/admin/facilities/' + currentEditFacilityId, {
                    method: 'PATCH',
                    body: JSON.stringify(payload)
                });
                await reloadAfterAction(data.message || 'Facility updated successfully.');
            } catch (error) {
                alert(error.message);
            }
        });
    </script>
</body>
</html>
