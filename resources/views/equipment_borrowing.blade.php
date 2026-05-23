<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My UNISched | Equipment Borrowing</title>
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
        .primary-button,
        .secondary-button { border: none; border-radius: 999px; padding: 0.65rem 1rem; font-weight: 800; cursor: pointer; transition: transform 180ms ease, filter 180ms ease; }
        .primary-button { background: linear-gradient(135deg, #8546ff, #5b21b6); color: #fff; }
        .secondary-button { background: rgba(15, 23, 42, 0.06); color: #0f172a; }
        .primary-button:hover,
        .secondary-button:hover { transform: translateY(-1px); }
        .form-input {
            width: 100%;
            border-radius: 18px;
            border: 1px solid #e9ddff;
            background: rgba(251, 249, 255, 0.92);
            padding: 0.85rem 1rem;
            color: #0f172a;
        }
        .form-input:focus { outline: none; border-color: rgba(133, 70, 255, 0.45); box-shadow: 0 0 0 4px rgba(133,70,255,0.1); }
        .btn-return, .btn-cancel {
            border: none;
            border-radius: 999px;
            padding: 0.55rem 0.95rem;
            font-weight: 800;
            cursor: pointer;
        }
        .btn-return { background: #dbeafe; color: #1d4ed8; }
        .btn-cancel { background: #fee2e2; color: #b91c1c; }
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
                                <li><a href="{{ route('library.status') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-book w-4"></i><span>Library Status</span></a></li>
                                <li><a href="{{ route('gym.status') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-dumbbell w-4"></i><span>Gym Status</span></a></li>
                                <li><a href="{{ route('canteen.status') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-utensils w-4"></i><span>Canteen Status</span></a></li>
                                <li><a href="{{ route('bao.status') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-building w-4"></i><span>BAO Status</span></a></li>
                                <li><a href="{{ route('equipment.borrowing') }}" class="dashboard-nav-link active flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-tools w-4"></i><span>Equipment Borrowing</span></a></li>
                                @if(auth()->user()->is_admin)
                                    <li><a href="{{ route('system.admin') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-cog w-4"></i><span>System Admin</span></a></li>
                                @endif
                            </ul>
                        </nav>
                        <div class="mt-4 rounded-2xl border border-violet-100 bg-violet-50/80 px-4 py-3">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Campus pulse</p>
                            <p class="mt-2 text-sm text-slate-600">Borrowing and return flows now follow the same elevated design language as the main dashboard.</p>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="flex-1">
                <div class="glass-panel rounded-[30px] border border-white/70 p-4 shadow-glow sm:p-6 lg:p-7">
                    <header class="flex flex-col gap-5 border-b border-violet-100 pb-5 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <p class="text-[0.7rem] font-bold uppercase tracking-[0.3em] text-violet-700">Equipment</p>
                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Equipment Borrowing</h1>
                                <span class="dashboard-pill"><i class="fas fa-calendar-alt"></i> 2026 A.Y.</span>
                            </div>
                            <p class="mt-2 max-w-2xl text-sm text-slate-600 sm:text-base">Borrow items only when they are available and track your returns from the same clean workspace.</p>
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

                    <section class="mt-6 grid gap-4 xl:grid-cols-3">
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Available equipment</p>
                            <p class="mt-3 text-3xl font-bold text-slate-900">{{ count($equipmentOptions) }}</p>
                            <p class="mt-2 text-sm text-slate-600">Items currently listed for borrowing.</p>
                        </div>
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Borrowed by you</p>
                            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $userBorrowed->count() }}</p>
                            <p class="mt-2 text-sm text-slate-600">Your currently active equipment requests.</p>
                        </div>
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Borrowed campus-wide</p>
                            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $borrowedEquipment->count() }}</p>
                            <p class="mt-2 text-sm text-slate-600">All approved equipment currently checked out.</p>
                        </div>
                    </section>

                    <section class="mt-6 grid gap-4 xl:grid-cols-[1fr_1fr_1.1fr]">
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Available Equipment</p>
                            <ul class="mt-4 space-y-3">
                                @foreach($equipmentOptions as $equipment)
                                    <li class="rounded-[18px] bg-violet-50/70 px-4 py-3">
                                        <p class="font-bold text-slate-900">{{ $equipment }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $borrowedEquipment->where('equipment_name', $equipment)->count() }} currently borrowed</p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Borrow Equipment</p>
                            <form id="borrow-form" action="{{ route('borrowings.store') }}" method="POST" class="mt-4 space-y-3">
                                @csrf
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Equipment</label>
                                    <select name="equipment" required class="form-input">
                                        @foreach($equipmentOptions as $equipment)
                                            <option value="{{ $equipment }}">{{ $equipment }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Return Date</label>
                                    <input type="date" name="return_date" required min="{{ now()->addDay()->format('Y-m-d') }}" class="form-input">
                                </div>
                                <button type="submit" class="primary-button w-full">Borrow</button>
                            </form>
                            <div id="borrow-result" class="mt-4 text-sm text-slate-600"></div>
                        </div>

                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">My Borrowed Equipment</p>
                            @if($userBorrowed->isEmpty())
                                <p class="mt-4 text-sm text-slate-600">You currently have no borrowed items or pending requests.</p>
                            @else
                                <ul class="mt-4 space-y-3">
                                    @foreach($userBorrowed as $item)
                                        <li class="rounded-[18px] bg-violet-50/70 px-4 py-3">
                                            <div class="flex flex-wrap items-center justify-between gap-3">
                                                <div>
                                                    <p class="font-bold text-slate-900">{{ $item->equipment_name }}</p>
                                                    <p class="mt-1 text-sm text-slate-600">
                                                        @if(!$item->is_approved)
                                                            Pending admin approval until {{ $item->return_date->format('M d, Y') }}
                                                        @else
                                                            Return by {{ $item->return_date->format('M d, Y') }}
                                                        @endif
                                                    </p>
                                                </div>
                                                @if($item->is_approved && !$item->return_requested)
                                                    <button class="btn-return" type="button" onclick="requestReturn({{ $item->id }})">Request Return</button>
                                                @elseif($item->is_approved && $item->return_requested)
                                                    <button class="btn-cancel" type="button" disabled>Return Requested</button>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
    <script>
        document.getElementById('borrow-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            const payload = new URLSearchParams(formData);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: payload.toString()
            })
            .then(async response => {
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    throw new Error('Server returned an error: ' + response.status);
                }
                document.getElementById('borrow-result').innerText = data.message || data.error;
                if (response.ok) {
                    document.getElementById('borrow-result').style.color = '#2f7b55';
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    document.getElementById('borrow-result').style.color = '#b32f2d';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('borrow-result').innerText = 'Unable to submit borrow request. Please try again.';
                document.getElementById('borrow-result').style.color = '#b32f2d';
            });
        });

        function requestReturn(borrowingId) {
            if (!confirm('Request return approval from an admin?')) {
                return;
            }

            fetch('/borrowings/' + borrowingId + '/request-return', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(async response => {
                const data = await response.json();
                document.getElementById('borrow-result').innerText = data.message || data.error;
                document.getElementById('borrow-result').style.color = response.ok ? '#2f7b55' : '#b32f2d';
                if (response.ok) {
                    setTimeout(() => window.location.reload(), 1200);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('borrow-result').innerText = 'Unable to send return request. Please try again.';
                document.getElementById('borrow-result').style.color = '#b32f2d';
            });
        }
    </script>
</body>
</html>
