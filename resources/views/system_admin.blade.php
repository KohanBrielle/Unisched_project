<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My UNISched | System Admin</title>
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
                    <li><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> <span>Facility Status Overview</span></a></li>
                    <li><a href="{{ route('activity.reservation') }}"><i class="far fa-calendar-alt"></i> <span>Activity Center Reservation</span></a></li>
                    <li><a href="{{ route('library.status') }}"><i class="fas fa-book"></i> <span>Library Status</span></a></li>
                    <li><a href="{{ route('gym.status') }}"><i class="fas fa-dumbbell"></i> <span>Gym Status</span></a></li>
                    <li><a href="{{ route('canteen.status') }}"><i class="fas fa-utensils"></i> <span>Canteen Status</span></a></li>
                    <li><a href="{{ route('bao.status') }}"><i class="fas fa-building"></i> <span>BAO Status</span></a></li>
                    <li><a href="{{ route('equipment.borrowing') }}"><i class="fas fa-tools"></i> <span>Equipment Borrowing</span></a></li>
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
                <h1>System Admin Panel</h1>
                <div class="user-profile">
                    <span class="year-badge">2026 A.Y. <i class="fas fa-chevron-right"></i></span>
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

            <section class="facility-grid">
                <div class="widget">
                    <div class="widget-header">User Management</div>
                    <p>Total Users: {{ \App\Models\User::count() }}</p>
                    <a href="#" class="btn">Manage Users</a>
                </div>

                <div class="widget">
                    <div class="widget-header">Facility Management</div>
                    <p>Total Facilities: {{ \App\Models\Facility::count() }}</p>
                    <a href="#" class="btn">Manage Facilities</a>
                </div>

                <div class="widget">
                    <div class="widget-header">System Logs</div>
                    <p>Recent Activity:</p>
                    <ul>
                        <li>User registered</li>
                        <li>Facility updated</li>
                        <li>Reservation approved</li>
                    </ul>
                </div>
            </section>
        </main>
    </div>
    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
</body>
</html>