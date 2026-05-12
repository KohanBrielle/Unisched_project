@extends('layouts.mobile')

@section('mobile-content')
<div class="calendar-view">
    <h1>Calendar</h1>

    <!-- Horizontal Date Picker -->
    <div class="date-picker">
        <div class="date-item">Mon<br>12</div>
        <div class="date-item active">Tue<br>13</div>
        <div class="date-item">Wed<br>14</div>
        <div class="date-item">Thu<br>15</div>
        <div class="date-item">Fri<br>16</div>
        <div class="date-item">Sat<br>17</div>
        <div class="date-item">Sun<br>18</div>
    </div>

    <!-- Vertical Time Slots -->
    <div class="time-slots">
        <div class="time-slot available">
            <span>9:00 AM - 10:00 AM</span>
            <span>Available</span>
        </div>
        <div class="time-slot reserved">
            <span>10:00 AM - 11:00 AM</span>
            <span>Reserved</span>
        </div>
        <div class="time-slot available">
            <span>11:00 AM - 12:00 PM</span>
            <span>Available</span>
        </div>
        <div class="time-slot closed">
            <span>12:00 PM - 1:00 PM</span>
            <span>Closed</span>
        </div>
        <!-- Add more slots -->
    </div>
</div>
@endsection