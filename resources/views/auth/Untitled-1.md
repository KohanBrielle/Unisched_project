Project Documentation: My UniSched
1. Core Project Identity
Project Name: My UniSched

Developers: BS CpE 2A - Group 2 (A.Y. 2025-2026)

Purpose: A specialized scheduling web application for LSPU students and faculty to manage room facilities and attendance logs.

Visual Theme:

Primary Colors: Deep Purple (#d05de7) and Gold/Yellow accents.

Aesthetic: Modern, semi-transparent (glassmorphism), featuring a video background (LSPU.mp4).

Typography: 'Anton' and 'Montserrat'.

2. Tech Stack
Frontend: HTML5, CSS3, JavaScript (ES6).

Backend: PHP 8.x, Laravel 11.x (using Laravel Breeze for Auth).

Database: MySQL 8.0.

Assets: Vite for frontend bundling.

3. Database Structure (unisched_db)
Current tables and key columns in MySQL:

users: Contains id, name, student_id, email, password, and remember_token.

facilities: Contains id, room_name, building, and capacity.

attendance_logs: Contains id, user_id, facility_id, time_in, and time_out.

migrations: Tracks database schema versions.

4. Key Files & Paths
Custom Styles: public/css/Group2_Style.css.

Custom Logic: public/js/Group2_js.js.

Authentication Views: resources/views/auth/ (Includes custom login.blade.php and register.blade.php).

Routes: routes/web.php and routes/auth.php.

Models: app/Models/User.php (Includes student_id in $fillable).

5. Planned Implementations
Authentication: Custom-styled Login and Registration forms linked to MySQL via Laravel Breeze.

Attendance Tracking: A system to log student entries into facilities using their student_id.

Admin Dashboard: A restricted area for faculty to view real-time facility occupancy.

Responsive Design: Ensuring the purple "glass" box adapts to mobile and desktop screens.

6. Advanced Feature Implementations
A. Facility Categorization & Calendar Logic
Borrowable Facilities (Reservation Enabled):

Target: Activity Center.

Feature: Clicking these opens a Calendar View showing approved reservation dates.

Action: Users can submit "Reservation Requests" via a dedicated Requests Tab.

Public/Service Facilities (Status Only):

Target: Gym, Library, Registrar, BAO, Canteen.

Feature: No calendar/reservation. Displays only Status Indicators: Open, Closed, or Lunch Break.

B. QR-Based Attendance & Occupancy System
Unique QR Generation: Upon registration, a unique QR code is generated for each user based on their student_id and encrypted user_id.

The "Double-Scan" Logic:

Scan 1 (Check-In): Creates a record in attendance_logs with time_in. Increments the occupancy_counter for that facility in the database.

Scan 2 (Check-Out): Updates the existing record with time_out. Decrements the occupancy_counter for that facility.

Occupancy Monitoring: The Dashboard Tab displays real-time occupancy counts using ajax polling (e.g., "Library: 45/100 students").

7. Expanded Database Schema Requirements
reservations: id, user_id, facility_id, start_time, end_time, status (pending/approved).

facilities (Updated): Added is_borrowable (boolean) and current_occupancy (integer).

1. For the Dashboard Logic:

"Based on PROJECT_CONTEXT.md, create a Laravel Blade loop for the dashboard. If a facility's is_borrowable is true, make it link to a calendar. If false, show a status badge for 'Open' or 'Closed' instead."

2. For the QR System:

"Using PHP and the simple-qrcode library, write a function in the RegisteredUserController that generates a unique QR code for a user using their student_id after they register."

3. For the Occupancy Counter:

"Write a Laravel Controller method that handles a QR scan. If the user has an open attendance_log (no time_out), set the time_out and decrement the facility's current_occupancy. Otherwise, create a new log and increment occupancy."