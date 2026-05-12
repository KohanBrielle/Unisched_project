<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .profile-btn {
            background: #7a5cf7;
            color: white;
            padding: 8px 14px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .user-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-end;
        }

        .user-actions a,
        .user-actions button {
            border: none;
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
            padding: 8px 12px;
            border-radius: 999px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s ease;
            font-weight: 600;
        }

        .user-actions a:hover,
        .user-actions button:hover {
            background: rgba(255, 255, 255, 0.24);
        }

        .user-actions button {
            width: 100%;
            text-align: center;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-open {
            background-color: #d4edda;
            color: #155724;
        }

        .status-closed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-reserved {
            background-color: #fff3cd;
            color: #856404;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            
            .admin-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .stat-card h3 {
                font-size: 1.8rem;
            }
            
            table {
                font-size: 0.9rem;
            }
            
            th, td {
                padding: 8px 6px;
            }
            
            .user-actions {
                flex-direction: row;
                justify-content: center;
                gap: 10px;
            }
            
            .user-actions a,
            .user-actions button {
                padding: 6px 10px;
                font-size: 0.9rem;
            }
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

            <header style="position: relative;">
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.7); z-index: -1;"></div>
                <h1><i class="fas fa-crown"></i> Admin Dashboard</h1>
                <div class="user-profile">
                    <span class="year-badge">Admin <i class="fas fa-shield-alt"></i></span>
                    <a href="{{ route('profile.edit') }}" class="avatar-link">
                        <img src="{{ auth()->user()->profile_picture_url }}" alt="Profile" class="avatar">
                    </a>
                    <div class="user-actions">
                        <a href="{{ route('profile.edit') }}" class="profile-btn">Profile</a>
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
                        <h3>{{ \App\Models\Reservation::whereIn('status', ['pending', 'approved'])->count() }}</h3>
                        <p>Current Reservations</p>
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

            <!-- Borrowed Equipment -->
            <section class="admin-section widget">
                <div class="widget-header">Borrowed Equipment</div>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Equipment</th>
                            <th>Borrowed At</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\BorrowedEquipment::where('status', 'borrowed')->where('is_approved', true)->where('return_requested', false)->get() as $item)
                            <tr>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->equipment_name }}</td>
                                <td>{{ optional($item->borrowed_at)->format('M d, Y H:i') ?? 'Pending approval' }}</td>
                                <td>{{ $item->return_date->format('M d, Y') }}</td>
                                <td>{{ ucfirst($item->status) }}</td>
                                <td>
                                    <span style="color: #555;">Awaiting return request</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <section class="admin-section widget">
                <div class="widget-header">Pending Borrow Requests</div>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Equipment</th>
                            <th>Requested Return</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\BorrowedEquipment::where('status', 'borrowed')->where('is_approved', false)->get() as $item)
                            <tr>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->equipment_name }}</td>
                                <td>{{ $item->return_date->format('M d, Y') }}</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td>
                                    <button class="action-btn" style="background: #27ae60; color: white;" onclick="approveBorrowing({{ $item->id }})">Approve</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <section class="admin-section widget">
                <div class="widget-header">Return Requests</div>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Equipment</th>
                            <th>Borrowed At</th>
                            <th>Return Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\BorrowedEquipment::where('return_requested', true)->get() as $item)
                            <tr>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->equipment_name }}</td>
                                <td>{{ optional($item->borrowed_at)->format('M d, Y H:i') ?? 'N/A' }}</td>
                                <td>{{ $item->return_date->format('M d, Y') }}</td>
                                <td>
                                    <button class="action-btn" style="background: #2d7a41; color: white;" onclick="approveReturnRequest({{ $item->id }})">Approve Return</button>
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
                            <th>Status</th>
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
                                <td>
                                    <span class="status-badge status-{{ $facility->status }}">
                                        {{ ucfirst($facility->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="action-btn edit-btn" onclick="openEditFacilityModal(this)"
                                        data-facility-id="{{ $facility->id }}"
                                        data-room-name="{{ $facility->room_name }}"
                                        data-building="{{ $facility->building }}"
                                        data-capacity="{{ $facility->capacity }}"
                                        data-current-occupancy="{{ $facility->current_occupancy }}"
                                        data-status="{{ $facility->status }}">
                                        Edit
                                    </button>
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
                                    <button class="action-btn" style="background: #e74c3c; color: white;" onclick="deleteReservation({{ $reservation->id }})">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <!-- Cleanup Section -->
            <section class="admin-section widget">
                <div class="widget-header">Data Cleanup</div>
                <div style="padding: 20px; display: flex; gap: 15px; flex-wrap: wrap;">
                    <button class="action-btn" style="background: #e74c3c; color: white;" onclick="cleanupAttendanceLogs()">
                        <i class="fas fa-trash"></i> Clear Attendance Logs
                    </button>
                    <button class="action-btn" style="background: #e67e22; color: white;" onclick="cleanupExpiredReservations()">
                        <i class="fas fa-calendar-times"></i> Clean Expired Reservations
                    </button>
                </div>
            </section>
        </main>
    </div>

    <!-- Edit Facility Modal -->
    <div id="editFacilityModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
        <div style="background-color:#fefefe; margin:5% auto; padding:20px; border:1px solid #888; width:90%; max-width:500px; border-radius:8px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h2 style="margin:0;">Edit Facility</h2>
                <button onclick="closeEditFacilityModal()" style="background:none; border:none; font-size:28px; cursor:pointer; color:#888;">&times;</button>
            </div>
            <form onsubmit="event.preventDefault(); saveFacilityChanges();">
                <input type="hidden" id="editFacilityId">
                <div style="margin-bottom:15px;">
                    <label for="editRoomName" style="display:block; margin-bottom:5px; font-weight:bold;">Room Name:</label>
                    <input type="text" id="editRoomName" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
                </div>
                <div style="margin-bottom:15px;">
                    <label for="editBuilding" style="display:block; margin-bottom:5px; font-weight:bold;">Building:</label>
                    <input type="text" id="editBuilding" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
                </div>
                <div style="margin-bottom:15px;">
                    <label for="editCapacity" style="display:block; margin-bottom:5px; font-weight:bold;">Capacity:</label>
                    <input type="number" id="editCapacity" required min="1" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
                </div>
                <div style="margin-bottom:15px;">
                    <label for="editCurrentOccupancy" style="display:block; margin-bottom:5px; font-weight:bold;">Current Occupancy:</label>
                    <input type="number" id="editCurrentOccupancy" required min="0" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
                </div>
                <div style="margin-bottom:15px;">
                    <label for="editStatus" style="display:block; margin-bottom:5px; font-weight:bold;">Status:</label>
                    <select id="editStatus" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                        <option value="reserved">Reserved</option>
                    </select>
                </div>
                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" onclick="closeEditFacilityModal()" style="padding:10px 20px; background-color:#888; color:white; border:none; border-radius:4px; cursor:pointer;">Cancel</button>
                    <button type="submit" style="padding:10px 20px; background-color:var(--primary-purple); color:white; border:none; border-radius:4px; cursor:pointer;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
    <script>
        let currentEditFacilityId = null;

        function openEditFacilityModal(button) {
            const facilityId = button.dataset.facilityId;
            const roomName = button.dataset.roomName || '';
            const building = button.dataset.building || '';
            const capacity = button.dataset.capacity || '0';
            const currentOccupancy = button.dataset.currentOccupancy || '0';
            const status = button.dataset.status || 'open';

            currentEditFacilityId = facilityId;
            document.getElementById('editFacilityId').value = facilityId;
            document.getElementById('editRoomName').value = roomName;
            document.getElementById('editBuilding').value = building;
            document.getElementById('editCapacity').value = parseInt(capacity, 10);
            document.getElementById('editCurrentOccupancy').value = parseInt(currentOccupancy, 10);
            document.getElementById('editStatus').value = status;
            document.getElementById('editFacilityModal').style.display = 'block';
        }

        function closeEditFacilityModal() {
            document.getElementById('editFacilityModal').style.display = 'none';
            currentEditFacilityId = null;
        }

        function saveFacilityChanges() {
            if (!currentEditFacilityId) return;

            const facilityId = document.getElementById('editFacilityId').value;
            const roomName = document.getElementById('editRoomName').value;
            const building = document.getElementById('editBuilding').value;
            const capacity = document.getElementById('editCapacity').value;
            const currentOccupancy = document.getElementById('editCurrentOccupancy').value;
            const status = document.getElementById('editStatus').value;

            fetch('/admin/facilities/' + facilityId, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    room_name: roomName,
                    building: building,
                    capacity: parseInt(capacity, 10),
                    current_occupancy: parseInt(currentOccupancy, 10),
                    status: status
                })
            }).then(async response => {
                if (response.ok) {
                    alert('Facility updated successfully!');
                    location.reload();
                    return;
                }

                const data = await response.json().catch(() => null);
                const message = data?.message || data?.error || 'Error updating facility';
                alert(message);
            }).catch(error => {
                console.error('Error:', error);
                alert('Error updating facility');
            });
        }

        window.onclick = function(event) {
            const modal = document.getElementById('editFacilityModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }

        function makeAdmin(userId) {
            if (confirm('Make this user an admin?')) {
                fetch('/admin/users/' + userId + '/make-admin', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (!response.ok) throw new Error('Request failed');
                    return response.ok;
                }).then(() => location.reload()).catch(error => {
                    console.error('Error:', error);
                    alert('Error making user an admin');
                });
            }
        }

        function removeAdmin(userId) {
            if (confirm('Remove admin privileges from this user?')) {
                fetch('/admin/users/' + userId + '/remove-admin', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (!response.ok) throw new Error('Request failed');
                    return response.ok;
                }).then(() => location.reload()).catch(error => {
                    console.error('Error:', error);
                    alert('Error removing admin privileges');
                });
            }
        }

        function deleteUser(userId) {
            if (confirm('Delete this user?')) {
                fetch('/admin/users/' + userId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (!response.ok) throw new Error('Request failed');
                    return response.ok;
                }).then(() => location.reload()).catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting user');
                });
            }
        }

        function deleteFacility(facilityId) {
            if (confirm('Delete this facility?')) {
                fetch('/admin/facilities/' + facilityId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (!response.ok) throw new Error('Request failed');
                    return response.ok;
                }).then(() => location.reload()).catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting facility');
                });
            }
        }

        function approveReservation(reservationId) {
            fetch('/admin/reservations/' + reservationId + '/approve', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => {
                if (!response.ok) throw new Error('Request failed');
                return response.ok;
            }).then(() => location.reload()).catch(error => {
                console.error('Error:', error);
                alert('Error approving reservation');
            });
        }

        function rejectReservation(reservationId) {
            fetch('/admin/reservations/' + reservationId + '/reject', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => {
                if (!response.ok) throw new Error('Request failed');
                return response.ok;
            }).then(() => location.reload()).catch(error => {
                console.error('Error:', error);
                alert('Error rejecting reservation');
            });
        }

        function deleteReservation(reservationId) {
            if (confirm('Delete this reservation permanently?')) {
                fetch('/admin/reservations/' + reservationId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (!response.ok) throw new Error('Request failed');
                    return response.ok;
                }).then(() => location.reload()).catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting reservation');
                });
            }
        }

        function approveBorrowing(borrowingId) {
            if (!confirm('Approve this borrow request?')) {
                return;
            }

            fetch('/admin/borrowings/' + borrowingId + '/approve', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(async response => {
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    throw new Error('Server returned an error: ' + response.status);
                }
                if (response.ok) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.error || 'Could not approve borrowing.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while approving the borrow request.');
            });
        }

        function approveReturnRequest(borrowingId) {
            if (!confirm('Approve this return request?')) {
                return;
            }

            fetch('/admin/borrowings/' + borrowingId + '/approve-return', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(async response => {
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    throw new Error('Server returned an error: ' + response.status);
                }
                if (response.ok) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.error || 'Could not approve return request.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while approving the return request.');
            });
        }

        function cleanupAttendanceLogs() {
            if (confirm('Are you sure you want to clear ALL attendance logs? This action cannot be undone.')) {
                fetch('{{ route('attendance.cleanup') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => response.json())
                  .then(data => {
                      alert(data.message);
                      location.reload();
                  })
                  .catch(error => {
                      console.error('Error:', error);
                      alert('Error clearing attendance logs');
                  });
            }
        }

        function cleanupExpiredReservations() {
            if (confirm('Are you sure you want to clean up expired reservations? This action cannot be undone.')) {
                fetch('{{ route('reservations.cleanup') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => response.json())
                  .then(data => {
                      alert(data.message);
                      location.reload();
                  })
                  .catch(error => {
                      console.error('Error:', error);
                      alert('Error cleaning up expired reservations');
                  });
            }
        }
    </script>
</body>
</html>