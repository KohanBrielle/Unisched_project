<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My UNISched | Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard_enhanced.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-section {
            margin-bottom: 30px;
        }

        .admin-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            margin: 0;
            color: var(--primary-purple);
            font-size: 2rem;
        }

        .stat-card p {
            margin: 5px 0 0 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: var(--primary-purple);
            color: white;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .action-btn {
            padding: 6px 12px;
            margin: 0 3px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .edit-btn {
            background: #4a90e2;
            color: white;
        }

        .delete-btn {
            background: #e74c3c;
            color: white;
        }

        .admin-btn {
            background: var(--primary-purple);
            color: white;
        }

        .remove-admin-btn {
            background: #f39c12;
            color: white;
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
                    <li><a href="{{ route('system.admin') }}"><i class="fas fa-cog"></i> <span>System Admin</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Background Video -->
            <video autoplay muted loop id="bg-video" style="position: fixed; top: 0; left: 0; min-width: 100%; min-height: 100%; z-index: -2; object-fit: cover;">
                <source src="{{ asset('videos/LSPU.mp4') }}" type="video/mp4">
            </video>

            <header>
                <h1><i class="fas fa-crown"></i> Admin Dashboard</h1>
                <div class="user-profile">
                    <span class="year-badge">Admin <i class="fas fa-shield-alt"></i></span>
                    <a href="{{ route('profile.edit') }}" class="avatar-link">
                        <img src="https://via.placeholder.com/40" alt="Profile" class="avatar">
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

            <!-- Statistics -->
            <section class="admin-section">
                <h3>Overview</h3>
                <div class="admin-grid">
                    <div class="stat-card">
                        <h3>{{ \App\Models\User::count() }}</h3>
                        <p>Total Users</p>
                    </div>
                    <div class="stat-card">
                        <h3>{{ \App\Models\User::where('is_admin', true)->count() }}</h3>
                        <p>Admins</p>
                    </div>
                    <div class="stat-card">
                        <h3>{{ \App\Models\Facility::count() }}</h3>
                        <p>Facilities</p>
                    </div>
                    <div class="stat-card">
                        <h3>{{ \App\Models\Reservation::count() }}</h3>
                        <p>Reservations</p>
                    </div>
                </div>
            </section>

            <!-- User Management -->
            <section class="admin-section widget">
                <div class="widget-header">User Management</div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Student ID</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\User::all() as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->student_id }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->is_admin ? 'Admin' : 'User' }}</td>
                                <td>
                                    @if(!$user->is_admin)
                                        <button class="action-btn admin-btn" onclick="makeAdmin({{ $user->id }})">Make Admin</button>
                                    @else
                                        <button class="action-btn remove-admin-btn" onclick="removeAdmin({{ $user->id }})">Remove Admin</button>
                                    @endif
                                    <button class="action-btn delete-btn" onclick="deleteUser({{ $user->id }})">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <!-- Facility Management -->
            <section class="admin-section widget">
                <div class="widget-header">Facility Management</div>
                <table>
                    <thead>
                        <tr>
                            <th>Room Name</th>
                            <th>Building</th>
                            <th>Capacity</th>
                            <th>Occupancy</th>
                            <th>Borrowable</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Facility::all() as $facility)
                            <tr>
                                <td>{{ $facility->room_name }}</td>
                                <td>{{ $facility->building }}</td>
                                <td>{{ $facility->capacity }}</td>
                                <td>{{ $facility->current_occupancy }}/{{ $facility->capacity }}</td>
                                <td>{{ $facility->is_borrowable ? 'Yes' : 'No' }}</td>
                                <td>
                                    <button class="action-btn edit-btn">Edit</button>
                                    <button class="action-btn delete-btn" onclick="deleteFacility({{ $facility->id }})">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <!-- Pending Reservations -->
            <section class="admin-section widget">
                <div class="widget-header">Pending Reservations</div>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Facility</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Reservation::where('status', 'pending')->get() as $reservation)
                            <tr>
                                <td>{{ $reservation->user->name }}</td>
                                <td>{{ $reservation->facility->room_name }}</td>
                                <td>{{ $reservation->start_time->format('M d, H:i') }}</td>
                                <td>{{ $reservation->end_time->format('M d, H:i') }}</td>
                                <td><span style="color: #f39c12;">Pending</span></td>
                                <td>
                                    <button class="action-btn" style="background: #27ae60; color: white;" onclick="approveReservation({{ $reservation->id }})">Approve</button>
                                    <button class="action-btn delete-btn" onclick="rejectReservation({{ $reservation->id }})">Reject</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
    <script>
        function makeAdmin(userId) {
            if (confirm('Make this user an admin?')) {
                fetch('/admin/users/' + userId + '/make-admin', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => location.reload());
            }
        }

        function removeAdmin(userId) {
            if (confirm('Remove admin privileges from this user?')) {
                fetch('/admin/users/' + userId + '/remove-admin', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => location.reload());
            }
        }

        function deleteUser(userId) {
            if (confirm('Delete this user?')) {
                fetch('/admin/users/' + userId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => location.reload());
            }
        }

        function deleteFacility(facilityId) {
            if (confirm('Delete this facility?')) {
                fetch('/admin/facilities/' + facilityId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => location.reload());
            }
        }

        function approveReservation(reservationId) {
            fetch('/admin/reservations/' + reservationId + '/approve', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => location.reload());
        }

        function rejectReservation(reservationId) {
            fetch('/admin/reservations/' + reservationId + '/reject', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => location.reload());
        }
    </script>
</body>
</html>