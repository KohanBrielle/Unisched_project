<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My UNISched | {{ $facility->room_name }} Status</title>
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
                    <li><a href="{{ route('equipment.borrowing') }}"><i class="fas fa-tools"></i> <span>Equipment Borrowing</span></a></li>
                    @if(auth()->user()->is_admin)
                        <li><a href="{{ route('system.admin') }}"><i class="fas fa-cog"></i> <span>System Admin</span></a></li>
                    @endif
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <div>
                    <p class="eyebrow">Facility Status</p>
                    <h1>{{ $facility->room_name }} Status</h1>
                    <p class="subtitle">Review current occupancy, upcoming bookings, and stay ready to check in with your QR code.</p>
                </div>
                <div class="user-profile">
                    <span class="year-badge">2026 A.Y.</span>
                    <a href="{{ route('profile.edit') }}" class="avatar-link">
                        <img src="{{ auth()->user()->profile_picture_url }}" alt="Profile" class="avatar">
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
                @php
                    $upcoming = $facility->reservations()->where('status', 'approved')->where('end_time', '>', now())->orderBy('start_time')->get();
                    $activeReservation = $facility->reservations()->where('status', 'approved')
                        ->where('start_time', '<=', now())
                        ->where('end_time', '>=', now())
                        ->exists();
                    $displayStatus = $facility->status;
                    if ($displayStatus === 'open' && $activeReservation) {
                        $displayStatus = 'reserved';
                    }
                @endphp
                <div class="card status-card {{ $displayStatus === 'open' ? 'green' : ($displayStatus === 'closed' ? 'red' : 'yellow') }}">
                    <div class="card-info">
                        <h3>{{ $facility->room_name }}</h3>
                        <p>Building: {{ $facility->building }}</p>
                        <p>Current occupancy: {{ $facility->current_occupancy }} / {{ $facility->capacity }}</p>
                        <p>Status: <span class="status-chip status-{{ $displayStatus }}">{{ ucfirst($displayStatus) }}</span></p>
                    </div>
                    <div class="progress-circle" data-percent="{{ round(($facility->current_occupancy / max($facility->capacity, 1)) * 100) }}">
                        <svg><circle cx="35" cy="35" r="30"></circle><circle cx="35" cy="35" r="30"></circle></svg>
                        <div class="number">{{ round(($facility->current_occupancy / max($facility->capacity, 1)) * 100) }}%</div>
                    </div>
                </div>

                @if($facility->room_name !== 'Canteen')
                    <div class="widget">
                        <div class="widget-header">Recent Attendance Logs</div>
                        @if($facility->attendanceLogs()->count() === 0)
                            <p class="empty-state">No attendance logs available.</p>
                        @else
                            <ul class="list-clean">
                                @foreach($facility->attendanceLogs()->latest()->take(5)->get() as $log)
                                    <li>
                                        <div class="item-title">{{ $log->user->name }}</div>
                                        <p class="item-subtitle">Checked in at {{ $log->time_in->format('H:i') }}@if($log->time_out) – out at {{ $log->time_out->format('H:i') }}@endif</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <div class="widget">
                    <div class="widget-header">Upcoming Bookings</div>
                    @if($upcoming->isEmpty())
                        <p class="empty-state">No upcoming approved reservations scheduled.</p>
                    @else
                        <ul class="list-clean">
                            @foreach($upcoming as $reservation)
                                <li>
                                    <div class="item-title">{{ $reservation->user->name }}</div>
                                    <p class="item-subtitle">{{ $reservation->start_time->format('M d, H:i') }} — {{ $reservation->end_time->format('H:i') }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="widget">
                    <div class="widget-header">Check In / Check Out</div>
                    <p class="empty-state" style="margin-bottom: 16px;">Use your camera to scan the QR code, or paste the payload manually.</p>

                    <div id="scanner-container" style="margin-bottom: 16px;">
                        <video id="qr-video" playsinline style="width:100%; border-radius:12px; background:#000; display:none;"></video>
                        <canvas id="qr-canvas" style="display:none;"></canvas>
                        <div style="display:flex; gap:10px; margin-top: 12px; flex-wrap:wrap;">
                            <button id="start-scan" type="button" class="btn" style="flex:1;">Start Camera Scan</button>
                            <button id="stop-scan" type="button" class="btn" style="flex:1; background:#dc2626;">Stop Camera</button>
                        </div>
                    </div>

                    <label for="qr-data-input" style="display:block; margin-bottom:8px; font-weight:600;">Manual QR Payload</label>
                    <textarea id="qr-data-input" rows="4" style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:8px; resize:vertical;" placeholder='Paste JSON payload from your QR code, e.g. {"student_id":"2024-12345","user_id":1}'></textarea>
                    <button id="scan-button" class="btn" style="margin-top:16px; width:100%;">Submit Payload</button>
                    <div id="scan-result" class="empty-state" style="margin-top: 16px;"></div>
                </div>
            </section>
        </main>
    </div>
    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        const video = document.getElementById('qr-video');
        const canvas = document.getElementById('qr-canvas');
        const scanResult = document.getElementById('scan-result');
        const startButton = document.getElementById('start-scan');
        const stopButton = document.getElementById('stop-scan');
        let scanning = false;
        let videoStream = null;
        let scanAnimationFrame = null;

        function showMessage(message, color = '#111') {
            scanResult.innerText = message;
            scanResult.style.color = color;
        }

        function stopCamera() {
            scanning = false;
            video.style.display = 'none';
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
            if (scanAnimationFrame) {
                cancelAnimationFrame(scanAnimationFrame);
            }
        }

        async function startCameraScan() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showMessage('Camera API not supported in this browser.', '#b32f2d');
                return;
            }

            try {
                videoStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                video.srcObject = videoStream;
                video.setAttribute('playsinline', true);
                video.style.display = 'block';
                await video.play();
                scanning = true;
                scanResult.innerText = 'Scanning for QR code...';
                scanFrame();
            } catch (error) {
                console.error('Camera error:', error);
                showMessage('Unable to access camera. Please allow camera permission or use manual payload.', '#b32f2d');
            }
        }

        function scanFrame() {
            if (!scanning) return;
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                const width = video.videoWidth;
                const height = video.videoHeight;
                canvas.width = width;
                canvas.height = height;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, width, height);
                const imageData = context.getImageData(0, 0, width, height);
                const code = jsQR(imageData.data, width, height, { inversionAttempts: 'attemptBoth' });

                if (code && code.data) {
                    stopCamera();
                    submitScan(code.data);
                    return;
                }
            }
            scanAnimationFrame = requestAnimationFrame(scanFrame);
        }

        function submitScan(qrData) {
            showMessage('Submitting scan...', '#1a56db');
            fetch('{{ route('facility.scan') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    facility_id: {{ $facility->id }},
                    qr_data: qrData,
                })
            })
            .then(async response => {
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    throw new Error('Server returned an error: ' + response.status);
                }
                if (response.ok) {
                    showMessage(data.message || 'Scan successful.', '#2f7b55');
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    showMessage(data.error || data.message || 'Scan failed.', '#b32f2d');
                }
            })
            .catch(error => {
                console.error('Scan submission error:', error);
                showMessage('An error occurred while submitting the scan.', '#b32f2d');
            });
        }

        document.getElementById('scan-button').addEventListener('click', function() {
            const qrData = document.getElementById('qr-data-input').value.trim();
            if (!qrData) {
                showMessage('Please paste the QR code payload first.', '#b32f2d');
                return;
            }
            submitScan(qrData);
        });

        startButton.addEventListener('click', startCameraScan);
        stopButton.addEventListener('click', stopCamera);

        window.addEventListener('beforeunload', stopCamera);
    </script>
</body>
</html>
