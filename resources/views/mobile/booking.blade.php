@extends('layouts.mobile')

@section('mobile-content')
<div class="booking-form">
    <!-- Trigger for bottom sheet, but since it's modal, perhaps this view is the form -->
    <h1>Book Facility</h1>

    <!-- Bottom Sheet for Booking -->
    <div class="bottom-sheet open">
        <h2>Activity Center Booking</h2>
        <form>
            <div class="picker">
                <label for="date">Select Date</label>
                <select id="date" name="date">
                    <option value="2023-05-12">May 12, 2023</option>
                    <option value="2023-05-13">May 13, 2023</option>
                    <!-- Add more dates -->
                </select>
            </div>
            <div class="picker">
                <label for="time">Select Time</label>
                <select id="time" name="time">
                    <option value="09:00">9:00 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <!-- Add more times -->
                </select>
            </div>
            <button type="submit">Confirm Booking</button>
        </form>
        <button onclick="closeBottomSheet()">Cancel</button>
    </div>
</div>
@endsection