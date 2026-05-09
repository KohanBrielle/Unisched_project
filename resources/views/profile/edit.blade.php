<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My UNISched | Profile</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard_enhanced.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        .profile-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-purple), var(--primary-purple-soft));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .profile-info h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .profile-info p {
            margin: 4px 0 0;
            color: var(--text-muted);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }

        .stat-card h3 {
            margin: 0 0 4px;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-purple);
        }

        .stat-card p {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .qr-section {
            text-align: center;
            background: linear-gradient(135deg, #f8f5ff, #eff0ff);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
        }

        .qr-code {
            width: 160px;
            height: 160px;
            margin: 20px auto;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background: white;
            padding: 12px;
        }

        .form-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        }

        .form-section h3 {
            margin: 0 0 16px;
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.2s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(95, 45, 145, 0.1);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary-purple);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-purple-soft);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .reservations-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .reservation-item {
            background: white;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .reservation-info h4 {
            margin: 0 0 4px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .reservation-info p {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }

        .form-group input[type="file"] {
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
        }

        .form-group input[type="file"]:focus {
            outline: none;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(95, 45, 145, 0.1);
        }

        .profile-picture-preview {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #e5e7eb;
        }
    </style>
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
                    <li><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> <span>Facility Status Overview</span></a></li>
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
                    <p class="eyebrow">Profile</p>
                    <h1>Manage Your Account</h1>
                    <p class="subtitle">Update your information, view your reservations, and manage your QR code.</p>
                </div>

                <div class="user-profile">
                    <span class="year-badge">2026 A.Y.</span>
                    <a href="{{ route('profile.edit') }}" class="avatar-link">
                        @if($user->profile_picture)
                            <img src="{{ asset('profile_pictures/' . $user->profile_picture) }}" alt="Profile" class="avatar">
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

            <!-- Profile Overview -->
            <div class="profile-section">
                <div class="profile-header">
                    <div class="profile-avatar">
                        @if($user->profile_picture)
                            <img src="{{ asset('profile_pictures/' . $user->profile_picture) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="profile-info">
                        <h1>{{ $user->name }}</h1>
                        <p>{{ $user->is_admin ? 'Administrator' : 'Student' }} • Student ID: {{ $user->student_id }}</p>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>{{ $reservations->count() }}</h3>
                        <p>Total Reservations</p>
                    </div>
                    <div class="stat-card">
                        <h3>{{ $reservations->where('status', 'approved')->count() }}</h3>
                        <p>Approved Reservations</p>
                    </div>
                    <div class="stat-card">
                        <h3>{{ $pendingReservations->count() }}</h3>
                        <p>Pending Reservations</p>
                    </div>
                    <div class="stat-card">
                        <h3>{{ $reservations->where('status', 'approved')->where('end_time', '>', now())->count() }}</h3>
                        <p>Active Reservations</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- QR Code Section -->
                <div class="lg:col-span-1">
                    <div class="qr-section">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">My QR Code</h3>
                        <p class="text-sm text-gray-600 mb-6">Use this QR code when checking in at facilities</p>

                        @if($qrUrl)
                            <img src="{{ $qrUrl }}" alt="Your QR Code" class="qr-code" />
                            <p class="text-xs text-gray-500 mt-4">Show this QR code to facility staff for quick check-in</p>
                        @else
                            <div class="qr-code flex items-center justify-center">
                                <i class="fas fa-qrcode text-4xl text-gray-400"></i>
                            </div>
                            <p class="text-xs text-gray-500 mt-4">QR code will be generated after registration</p>
                        @endif
                    </div>
                </div>

                <!-- Forms Section -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Update Profile Information -->
                    <div class="form-section">
                        <h3>Profile Information</h3>
                        <p class="text-sm text-gray-600 mb-6">Update your account details and email address.</p>

                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="profile_picture">Profile Picture</label>
                                <input id="profile_picture" name="profile_picture" type="file" accept="image/*" />
                                <p class="text-xs text-gray-500 mt-1">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</p>
                                @error('profile_picture')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                @if($user->profile_picture)
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-600">Current profile picture:</p>
                                        <img src="{{ asset('profile_pictures/' . $user->profile_picture) }}" alt="Current profile picture" class="mt-1 rounded-lg" style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>

                            <div class="flex gap-4">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>

                    <!-- Update Password -->
                    <div class="form-section">
                        <h3>Update Password</h3>
                        <p class="text-sm text-gray-600 mb-6">Ensure your account is using a secure password.</p>

                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input id="current_password" name="current_password" type="password" autocomplete="current-password" />
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input id="password" name="password" type="password" autocomplete="new-password" />
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
                                @error('password_confirmation')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex gap-4">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Pending Reservations -->
            <div class="profile-section">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Pending Reservations</h3>
                <p class="text-sm text-gray-600 mb-6">Reservations waiting for approval.</p>

                @if($pendingReservations->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>You don't have any pending reservations at the moment.</p>
                    </div>
                @else
                    <div class="reservations-list">
                        @foreach($pendingReservations as $reservation)
                            <div class="reservation-item">
                                <div class="reservation-info">
                                    <h4>{{ $reservation->facility->room_name }}</h4>
                                    <p>{{ $reservation->facility->building }} • {{ $reservation->start_time->format('M d, Y H:i') }} — {{ $reservation->end_time->format('M d, Y H:i') }}</p>
                                </div>
                                <span class="status-badge status-pending">Pending</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Reservation History -->
            <div class="profile-section">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Reservation History</h3>
                <p class="text-sm text-gray-600 mb-6">All your recent reservations and their status.</p>

                @if($reservations->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-history"></i>
                        <p>You haven't made any reservations yet.</p>
                    </div>
                @else
                    <div class="reservations-list">
                        @foreach($reservations as $reservation)
                            <div class="reservation-item">
                                <div class="reservation-info">
                                    <h4>{{ $reservation->facility->room_name }}</h4>
                                    <p>{{ $reservation->facility->building }} • {{ $reservation->start_time->format('M d, Y H:i') }} — {{ $reservation->end_time->format('M d, Y H:i') }}</p>
                                </div>
                                <span class="status-badge status-{{ $reservation->status }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Delete Account -->
            <div class="form-section">
                <h3 class="text-red-600">Delete Account</h3>
                <p class="text-sm text-gray-600 mb-6">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>

                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" />
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
</body>
</html>