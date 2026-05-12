@extends('layouts.mobile')

@section('mobile-content')
<div class="dashboard">
    <h1>My UNISched</h1>
    <p>Welcome back! Here's your facility status.</p>

    <div class="facility-cards">
        <!-- Activity Center Card -->
        <div class="facility-card glassmorphism">
            <h3>Activity Center</h3>
            <div class="progress-circle">75%</div>
            <span class="status-badge status-open">Open</span>
            <p>Occupancy: 75%</p>
            <button onclick="openBottomSheet()">Book Now</button>
        </div>

        <!-- Gym Card -->
        <div class="facility-card glassmorphism">
            <h3>Gym</h3>
            <div class="progress-circle">50%</div>
            <span class="status-badge status-reserved">Reserved</span>
            <p>Occupancy: 50%</p>
        </div>

        <!-- Library Card -->
        <div class="facility-card glassmorphism">
            <h3>Library</h3>
            <div class="progress-circle">90%</div>
            <span class="status-badge status-reserved">Reserved</span>
            <p>Occupancy: 90%</p>
        </div>
    </div>
</div>

<!-- Booking Bottom Sheet (hidden by default) -->
<div class="bottom-sheet" id="booking-sheet">
    <h2>Activity Center Booking</h2>
    <div class="picker">
        <label>Date</label>
        <select>
            <option>Today</option>
            <option>Tomorrow</option>
            <!-- Add more dates -->
        </select>
    </div>
    <div class="picker">
        <label>Time</label>
        <select>
            <option>9:00 AM</option>
            <option>10:00 AM</option>
            <!-- Add more times -->
        </select>
    </div>
    <button onclick="closeBottomSheet()">Book Now</button>
</div>
@endsection