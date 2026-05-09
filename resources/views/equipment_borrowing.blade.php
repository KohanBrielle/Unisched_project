<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My UNISched | Equipment Borrowing</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard_enhanced.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
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
                    <li class="active"><a href="{{ route('equipment.borrowing') }}"><i class="fas fa-tools"></i> <span>Equipment Borrowing</span></a></li>
                    @if(auth()->user()->is_admin)
                        <li><a href="{{ route('system.admin') }}"><i class="fas fa-cog"></i> <span>System Admin</span></a></li>
                    @endif
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <div>
                    <p class="eyebrow">Equipment</p>
                    <h1>Equipment Borrowing</h1>
                    <p class="subtitle">Borrow items only when they are available and track your returns.</p>
                </div>
                <div class="user-profile">
                    <span class="year-badge">2026 A.Y.</span>
                    <a href="{{ route('profile.edit') }}" class="avatar-link">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('profile_pictures/' . auth()->user()->profile_picture) }}" alt="Profile" class="avatar">
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

            <section class="facility-grid">
                <div class="widget">
                    <div class="widget-header">Available Equipment</div>
                    <ul class="list-clean">
                        @foreach($equipmentOptions as $equipment)
                            <li>
                                <div class="item-title">{{ $equipment }}</div>
                                <p class="item-subtitle">{{ $borrowedEquipment->where('equipment_name', $equipment)->count() }} currently borrowed</p>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="widget">
                    <div class="widget-header">Borrow Equipment</div>
                    <form id="borrow-form" action="{{ route('borrowings.store') }}" method="POST">
                        @csrf
                        <label>Equipment</label>
                        <select name="equipment" required>
                            @foreach($equipmentOptions as $equipment)
                                <option value="{{ $equipment }}">{{ $equipment }}</option>
                            @endforeach
                        </select>

                        <label>Return Date</label>
                        <input type="date" name="return_date" required min="{{ now()->addDay()->format('Y-m-d') }}">
                        <button type="submit" class="btn">Borrow</button>
                    </form>
                    <div id="borrow-result" class="empty-state" style="margin-top: 16px;"></div>
                </div>

                <div class="widget">
                    <div class="widget-header">My Borrowed Equipment</div>
                    @if($userBorrowed->isEmpty())
                        <p class="empty-state">You currently have no borrowed items or pending requests.</p>
                    @else
                        <ul class="list-clean">
                            @foreach($userBorrowed as $item)
                                <li>
                                    <div class="item-title">{{ $item->equipment_name }}</div>
                                    <p class="item-subtitle">
                                        @if(!$item->is_approved)
                                            Pending admin approval until {{ $item->return_date->format('M d, Y') }}
                                        @else
                                            Return by {{ $item->return_date->format('M d, Y') }}
                                        @endif
                                    </p>
                                    @if($item->is_approved && !$item->return_requested)
                                        <button class="btn request-return-btn" type="button" onclick="requestReturn({{ $item->id }})">Request Return</button>
                                    @elseif($item->is_approved && $item->return_requested)
                                        <button class="btn" type="button" disabled>Return Requested</button>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </section>
        </main>
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
