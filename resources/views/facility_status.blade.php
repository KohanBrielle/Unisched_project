<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My UNISched | {{ $facility->room_name }}</title>
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
        .status-chip { display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; padding: 0.15rem 0.8rem; font-size: 0.75rem; font-weight: 800; }
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
        .progress-circle circle:last-child { stroke: #8546ff; stroke-dasharray: 188.4; stroke-dashoffset: 188.4; transition: stroke-dashoffset 0.8s ease; }
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
        .form-input:focus { outline: none; border-color: rgba(133, 70, 255, 0.45); box-shadow: 0 0 0 4px rgba(133,70,255,0.1); }
        .scan-preview {
            min-height: 260px;
            border-radius: 24px;
            display: grid;
            place-items: center;
            padding: 24px;
            text-align: center;
            background: linear-gradient(135deg, #1b1332 0%, #2d1e4b 100%);
            color: rgba(255,255,255,0.95);
        }
        .scan-preview .camera-icon {
            width: 56px;
            height: 56px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.08);
            margin-bottom: 14px;
            font-size: 1.2rem;
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
                            <p class="mt-2 text-sm text-slate-600">Facility status pages now match the same polished console theme as the dashboard.</p>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="flex-1">
                <div class="glass-panel rounded-[30px] border border-white/70 p-4 shadow-glow sm:p-6 lg:p-7">
                    <header class="flex flex-col gap-5 border-b border-violet-100 pb-5 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <p class="text-[0.7rem] font-bold uppercase tracking-[0.3em] text-violet-700">{{ $facility->room_name }}</p>
                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">{{ $facility->room_name }}</h1>
                                <span class="dashboard-pill"><i class="fas fa-calendar-alt"></i> 2026 A.Y.</span>
                            </div>
                            <p class="mt-2 max-w-2xl text-sm text-slate-600 sm:text-base">Monitor occupancy, attendance, and upcoming activity from your campus console.</p>
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
                        $upcoming = $facility->reservations()->where('status', 'approved')->where('end_time', '>', now())->orderBy('start_time')->get();
                        $activeReservation = $facility->reservations()->where('status', 'approved')->where('start_time', '<=', now())->where('end_time', '>=', now())->exists();
                        $displayStatus = $facility->status;
                        if ($displayStatus === 'open' && $activeReservation) {
                            $displayStatus = 'reserved';
                        }
                        $occupancyPercent = (int) round(($facility->current_occupancy / max($facility->capacity, 1)) * 100);
                        $attendanceCount = $facility->attendanceLogs()->count();
                        $upcomingCount = $upcoming->count();
                    @endphp

                    <section class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-sm text-slate-500">Current occupants</p>
                            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $facility->current_occupancy }}</p>
                            <p class="mt-2 text-sm text-violet-700">Live occupancy count</p>
                        </div>
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-sm text-slate-500">Occupancy rate</p>
                            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $occupancyPercent }}%</p>
                            <p class="mt-2 text-sm text-emerald-700">Capacity utilization</p>
                        </div>
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-sm text-slate-500">Attendance logs</p>
                            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $attendanceCount }}</p>
                            <p class="mt-2 text-sm text-amber-700">Recorded check-ins</p>
                        </div>
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-sm text-slate-500">Upcoming bookings</p>
                            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $upcomingCount }}</p>
                            <p class="mt-2 text-sm text-rose-700">Approved reservations</p>
                        </div>
                    </section>

                    <section class="mt-6 grid gap-4 xl:grid-cols-[1.1fr_0.9fr]">
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Facility snapshot</p>
                                    <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $facility->building }}</h2>
                                    <p class="mt-1 text-sm text-slate-600">Capacity: {{ $facility->capacity }}</p>
                                </div>
                                <span class="status-chip status-{{ $displayStatus }}">{{ ucfirst($displayStatus) }}</span>
                            </div>
                            <p class="mt-4 text-sm leading-6 text-slate-600">
                                @if($displayStatus === 'open')
                                    The facility is open and ready for walk-ins.
                                @elseif($displayStatus === 'reserved')
                                    A booking is active right now.
                                @elseif($displayStatus === 'lunch_break')
                                    A lunch break is currently in effect.
                                @else
                                    The facility is currently closed.
                                @endif
                            </p>
                            <div class="mt-4 flex items-center justify-between gap-4 rounded-[20px] bg-violet-50/80 px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Occupancy progress</p>
                                    <p class="mt-1 text-sm text-slate-600">The current usage level is being refreshed live.</p>
                                </div>
                                <div class="progress-circle" data-percent="{{ $occupancyPercent }}">
                                    <svg><circle cx="35" cy="35" r="30"></circle><circle cx="35" cy="35" r="30"></circle></svg>
                                    <div class="number">{{ $occupancyPercent }}%</div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Recent Attendance Logs</p>
                            @if($attendanceCount === 0)
                                <p class="mt-4 text-sm text-slate-600">No attendance logs are available yet. Once students check in, their recent activity will appear here.</p>
                            @else
                                <ul class="mt-4 space-y-3">
                                    @foreach($facility->attendanceLogs()->latest()->take(5)->get() as $log)
                                        <li class="rounded-[18px] bg-violet-50/70 px-4 py-3">
                                            <p class="font-bold text-slate-900">{{ $log->user->name }}</p>
                                            <p class="mt-1 text-sm text-slate-600">@if($log->time_out) Checked out at {{ $log->time_out->format('H:i') }} @else Still in the facility @endif</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </section>

                    <section class="mt-6 grid gap-4 xl:grid-cols-[0.85fr_1.15fr_0.95fr]">
                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Upcoming Bookings</p>
                            @if($upcomingCount === 0)
                                <p class="mt-4 text-sm text-slate-600">No approved bookings are scheduled right now. The next reservation will appear here automatically.</p>
                            @else
                                <ul class="mt-4 space-y-3">
                                    @foreach($upcoming as $reservation)
                                        <li class="rounded-[18px] bg-violet-50/70 px-4 py-3">
                                            <p class="font-bold text-slate-900">{{ $reservation->user->name }}</p>
                                            <p class="mt-1 text-sm text-slate-600">{{ $reservation->start_time->format('M d, H:i') }} — {{ $reservation->end_time->format('H:i') }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">QR Scanner Controller</p>
                                    <h2 class="mt-2 text-lg font-bold text-slate-900">Live camera preview</h2>
                                </div>
                                <i class="fas fa-camera text-violet-700"></i>
                            </div>
                            <div class="scan-preview mt-4">
                                <div>
                                    <div class="camera-icon"><i class="fas fa-camera"></i></div>
                                    <h3 class="text-lg font-bold">Live camera preview</h3>
                                    <p class="mt-2 text-sm text-white/90">Start the camera scan to capture a QR code and update the attendance log instantly.</p>
                                </div>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <button id="start-scan" type="button" class="primary-button"><i class="fas fa-camera"></i> Start Camera Scan</button>
                                <button id="stop-scan" type="button" class="secondary-button"><i class="fas fa-power-off"></i> Stop Camera</button>
                            </div>
                        </div>

                        <div class="rounded-[24px] bg-white/90 p-5 shadow-[0_18px_46px_rgba(63,31,122,0.12)] ring-1 ring-white/80">
                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.28em] text-violet-700">Manual QR Payload</p>
                            <label for="qr-data-input" class="mt-4 mb-2 block text-sm font-semibold text-slate-700">Paste the QR payload here</label>
                            <textarea id="qr-data-input" class="form-input min-h-[160px]" placeholder='Paste JSON payload here, e.g. {"student_id":"2024-12345","user_id":1}'></textarea>
                            <button id="scan-button" type="button" class="primary-button mt-4 w-full">Submit Payload</button>
                            <p id="scan-result" class="mt-4 text-sm text-slate-600">Scan status will appear here after the camera or manual payload is used.</p>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <video id="qr-video" playsinline class="hidden"></video>
    <canvas id="qr-canvas" class="hidden"></canvas>

    <script src="{{ asset('js/dashboard_enhanced.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        const video = document.getElementById('qr-video');
        const canvas = document.getElementById('qr-canvas');
        const scanResult = document.getElementById('scan-result');
        const startButton = document.getElementById('start-scan');
        const stopButton = document.getElementById('stop-scan');
        const placeholder = document.querySelector('.scan-preview');
        let scanning = false;
        let videoStream = null;
        let scanAnimationFrame = null;

        function showMessage(message, color = '#0f172a') {
            scanResult.innerText = message;
            scanResult.style.color = color;
        }

        function stopCamera() {
            scanning = false;
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
            if (scanAnimationFrame) {
                cancelAnimationFrame(scanAnimationFrame);
            }
            video.classList.add('hidden');
            placeholder.innerHTML = `
                <div>
                    <div class="camera-icon"><i class="fas fa-camera"></i></div>
                    <h3 class="text-lg font-bold">Live camera preview</h3>
                    <p class="mt-2 text-sm text-white/90">Start the camera scan to capture a QR code and update the attendance log instantly.</p>
                </div>`;
        }

        async function startCameraScan() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showMessage('Camera API not supported in this browser.', '#b32f2d');
                return;
            }

            try {
                videoStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                video.srcObject = videoStream;
                video.classList.remove('hidden');
                placeholder.innerHTML = '';
                placeholder.appendChild(video);
                await video.play();
                scanning = true;
                showMessage('Scanning for QR code...');
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
            showMessage('Submitting scan...', '#1d4ed8');
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
