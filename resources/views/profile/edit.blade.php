<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My UNISched | Profile</title>
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
        .secondary-button,
        .danger-button {
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

        .danger-button {
            background: #dc2626;
            color: #fff;
        }

        .primary-button:hover,
        .secondary-button:hover,
        .danger-button:hover {
            transform: translateY(-1px);
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

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.15rem 0.8rem;
            font-size: 0.75rem;
            font-weight: 800;
        }

        .status-approved { background: rgba(34, 197, 94, 0.14); color: #166534; }
        .status-pending { background: rgba(245, 158, 11, 0.18); color: #92400e; }
        .status-rejected { background: rgba(248, 113, 113, 0.16); color: #991b1b; }
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
                                <li><a href="{{ route('equipment.borrowing') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-tools w-4"></i><span>Equipment Borrowing</span></a></li>
                                @if(auth()->user()->is_admin)
                                    <li><a href="{{ route('system.admin') }}" class="dashboard-nav-link flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700"><i class="fas fa-cog w-4"></i><span>System Admin</span></a></li>
                                @endif
                            </ul>
                        </nav>
                        <div class="mt-4 rounded-2xl border border-violet-100 bg-violet-50/80 px-4 py-3">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Campus pulse</p>
                            <p class="mt-2 text-sm text-slate-600">Update your account details and keep your campus profile in sync with the same polished console theme.</p>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="flex-1">
                <div class="glass-panel rounded-[30px] border border-white/70 p-4 shadow-glow sm:p-6 lg:p-7">
                    <header class="flex flex-col gap-5 border-b border-violet-100 pb-5 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <p class="text-[0.7rem] font-bold uppercase tracking-[0.3em] text-violet-700">Profile</p>
                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Manage Your Account</h1>
                                <span class="dashboard-pill"><i class="fas fa-calendar-alt"></i> 2026 A.Y.</span>
                            </div>
                            <p class="mt-2 max-w-2xl text-sm text-slate-600 sm:text-base">Update your information, review your reservations, and manage your QR code from one elevated workspace.</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center gap-3 rounded-full bg-white/80 px-3 py-2 shadow-sm ring-1 ring-violet-100">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3">
                                    <img src="{{ $user->profile_picture_url }}" alt="Profile" class="h-10 w-10 rounded-full object-cover ring-2 ring-violet-100">
                                    <div class="text-left">
                                        <p class="text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                                        <p class="text-[0.7rem] uppercase tracking-[0.2em] text-slate-500">{{ $user->is_admin ? 'Administrator' : 'Student' }}</p>
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

                    <section class="mt-6 rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                        <div class="flex flex-wrap items-center gap-4 border-b border-violet-100 pb-4">
                            <div class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-violet-600 to-violet-900 text-lg font-bold text-white">
                                <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                                <p class="text-sm text-slate-600">{{ $user->is_admin ? 'Administrator' : 'Student' }} • Student ID: {{ $user->student_id }}</p>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-[20px] bg-violet-50/80 p-4">
                                <p class="text-sm text-slate-500">Total Reservations</p>
                                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $reservations->count() }}</p>
                            </div>
                            <div class="rounded-[20px] bg-violet-50/80 p-4">
                                <p class="text-sm text-slate-500">Approved Reservations</p>
                                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $reservations->where('status', 'approved')->count() }}</p>
                            </div>
                            <div class="rounded-[20px] bg-violet-50/80 p-4">
                                <p class="text-sm text-slate-500">Pending Reservations</p>
                                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $pendingReservations->count() }}</p>
                            </div>
                            <div class="rounded-[20px] bg-violet-50/80 p-4">
                                <p class="text-sm text-slate-500">Active Reservations</p>
                                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $reservations->where('status', 'approved')->where('end_time', '>', now())->count() }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="mt-6 grid gap-4 xl:grid-cols-[0.9fr_1.1fr]">
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">My QR Code</p>
                            <p class="mt-2 text-sm text-slate-600">Use this QR code when checking in at facilities.</p>
                            <div class="mt-4 rounded-[22px] bg-gradient-to-br from-violet-50 to-white p-5 text-center">
                                @if($qrUrl)
                                    <img src="{{ $qrUrl }}" alt="Your QR Code" class="mx-auto h-44 w-44 rounded-2xl bg-white p-3 shadow-[0_10px_38px_rgba(76,37,141,0.14)]">
                                    <p class="mt-4 text-sm text-slate-600">Show this QR code to facility staff for quick check-in.</p>
                                @else
                                    <div class="mx-auto flex h-44 w-44 items-center justify-center rounded-2xl bg-white text-violet-300 shadow-[0_10px_38px_rgba(76,37,141,0.14)]">
                                        <i class="fas fa-qrcode text-5xl"></i>
                                    </div>
                                    <p class="mt-4 text-sm text-slate-600">QR code will be generated after registration.</p>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                                <div>
                                    <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Profile Information</p>
                                    <h2 class="mt-2 text-lg font-bold text-slate-900">Update your account details</h2>
                                </div>
                                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-4 space-y-3">
                                    @csrf
                                    @method('patch')

                                    <div>
                                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Name</label>
                                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="form-input">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="form-input">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="profile_picture" class="mb-2 block text-sm font-semibold text-slate-700">Profile Picture</label>
                                        <input id="profile_picture" name="profile_picture" type="file" accept="image/*" class="form-input">
                                        <p class="mt-2 text-xs text-slate-500">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</p>
                                        @error('profile_picture')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        @if($user->profile_picture)
                                            <div class="mt-3">
                                                <p class="text-sm text-slate-600">Current profile picture:</p>
                                                <img src="{{ $user->profile_picture_url }}" alt="Current profile picture" class="mt-2 h-20 w-20 rounded-2xl object-cover ring-1 ring-violet-100">
                                            </div>
                                        @endif
                                    </div>

                                    <button type="submit" class="primary-button">Save Changes</button>
                                </form>
                            </div>

                            <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                                <div>
                                    <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Update Password</p>
                                    <h2 class="mt-2 text-lg font-bold text-slate-900">Keep your account secure</h2>
                                </div>
                                <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-3">
                                    @csrf
                                    @method('put')

                                    <div>
                                        <label for="current_password" class="mb-2 block text-sm font-semibold text-slate-700">Current Password</label>
                                        <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="form-input">
                                        @error('current_password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">New Password</label>
                                        <input id="password" name="password" type="password" autocomplete="new-password" class="form-input">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Confirm New Password</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="form-input">
                                        @error('password_confirmation')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit" class="primary-button">Update Password</button>
                                </form>
                            </div>
                        </div>
                    </section>

                    <section class="mt-6 rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Pending Reservations</p>
                                <h2 class="mt-2 text-lg font-bold text-slate-900">Reservations waiting for approval</h2>
                            </div>
                        </div>
                        @if($pendingReservations->isEmpty())
                            <div class="mt-4 rounded-[20px] bg-violet-50/80 px-4 py-6 text-center text-sm text-slate-600">
                                <i class="fas fa-calendar-times mb-2 block text-xl text-violet-500"></i>
                                You don't have any pending reservations at the moment.
                            </div>
                        @else
                            <div class="mt-4 space-y-3">
                                @foreach($pendingReservations as $reservation)
                                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-[18px] bg-violet-50/70 px-4 py-3">
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $reservation->facility->room_name }}</p>
                                            <p class="mt-1 text-sm text-slate-600">{{ $reservation->facility->building }} • {{ $reservation->start_time->format('M d, Y H:i') }} — {{ $reservation->end_time->format('M d, Y H:i') }}</p>
                                        </div>
                                        <span class="status-badge status-pending">Pending</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section class="mt-6 rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Reservation History</p>
                                <h2 class="mt-2 text-lg font-bold text-slate-900">All recent reservations</h2>
                            </div>
                            @if($reservations->isNotEmpty())
                                <button type="button" onclick="clearReservationHistory()" class="inline-flex items-center gap-2 rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white">
                                    <i class="fas fa-trash-alt"></i> Clear History
                                </button>
                            @endif
                        </div>

                        @if($reservations->isEmpty())
                            <div class="mt-4 rounded-[20px] bg-violet-50/80 px-4 py-6 text-center text-sm text-slate-600">
                                <i class="fas fa-history mb-2 block text-xl text-violet-500"></i>
                                You haven't made any reservations yet.
                            </div>
                        @else
                            <div class="mt-4 space-y-3">
                                @foreach($reservations as $reservation)
                                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-[18px] bg-violet-50/70 px-4 py-3">
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $reservation->facility->room_name }}</p>
                                            <p class="mt-1 text-sm text-slate-600">{{ $reservation->facility->building }} • {{ $reservation->start_time->format('M d, Y H:i') }} — {{ $reservation->end_time->format('M d, Y H:i') }}</p>
                                        </div>
                                        <span class="status-badge status-{{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section class="mt-6 rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                        <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Delete Account</p>
                        <h2 class="mt-2 text-lg font-bold text-slate-900">Permanently remove your account</h2>
                        <p class="mt-2 text-sm text-slate-600">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>

                        <form method="post" action="{{ route('profile.destroy') }}" class="mt-4">
                            @csrf
                            @method('delete')

                            <div class="max-w-md">
                                <label for="delete_password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                                <input id="delete_password" name="password" type="password" autocomplete="current-password" class="form-input">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="danger-button mt-4">Delete Account</button>
                        </form>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
    <script>
        function clearReservationHistory() {
            if (confirm('Are you sure you want to clear all your reservation history? This action cannot be undone.')) {
                fetch('{{ route("profile.reservations.clear") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        location.reload();
                    } else if (data.error) {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while clearing reservation history.');
                });
            }
        }
    </script>
</body>
</html>
