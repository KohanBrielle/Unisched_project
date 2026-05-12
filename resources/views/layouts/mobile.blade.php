@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My UNISched - Mobile</title>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        @yield('mobile-content')
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="#" class="nav-item active">
            <span class="nav-icon">🏠</span>
            Home
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">📅</span>
            Reservations
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">🛠️</span>
            Equipment
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">👤</span>
            Profile
        </a>
    </nav>

    <script>
        // Simple script to handle bottom sheet
        function openBottomSheet() {
            document.querySelector('.bottom-sheet').classList.add('open');
        }
        function closeBottomSheet() {
            document.querySelector('.bottom-sheet').classList.remove('open');
        }
    </script>
</body>
</html>
@endsection