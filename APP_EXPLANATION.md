# UNISched Complete Project Explanation

## 1. Project Overview

UNISched is a Laravel-based campus management web application designed to help students and administrators manage facility access, reservations, equipment borrowing, attendance, and assistance requests in one system.

The application is built around a classic MVC-style structure:

- **Laravel routes** handle incoming URLs and route them to the correct controller or view.
- **Controllers** contain the request handling logic, validation, and decisions.
- **Models** represent database tables and encapsulate the business logic connected to those records.
- **Blade views** render the HTML pages shown to the user.
- **JavaScript** adds interactivity and live UI updates.
- **Tailwind CSS** provides the styling and responsive layout.

The main idea of the app is simple: the **server is the source of truth**, while the **frontend makes the experience dynamic and easy to use**.

---

## 2. Why the Website Was Built This Way

The project uses Laravel because it provides a solid foundation for:

- routing
- authentication
- validation
- session management
- database interaction
- secure request handling
- clean separation between layers

Blade is used for the views because it allows the app to combine HTML with PHP logic in a readable and maintainable way.

JavaScript is used for two purposes:

1. **frontend interactivity** such as updating the sidebar, handling modal actions, and sending AJAX requests
2. **live refresh** so the dashboard stays updated without the user manually reloading the page

Tailwind CSS is used so the UI can be styled quickly and consistently while remaining responsive.

---

## 3. End-to-End User Flow

### 3.1 Opening the Website

When a user visits the app, the root route checks whether the user is already authenticated.

- If the user is **not logged in**, they are shown the login page.
- If the user **is logged in**, they are redirected to the dashboard.

This makes the entry point simple and ensures that the dashboard only appears to authenticated users.

### 3.2 Registering a New Account

A new user can go to the registration page and submit:

- full name
- student ID
- email
- password

The registration flow validates the input before saving anything.

The backend ensures that:

- the student ID is unique
- the email is unique
- the password follows Laravel's default password rules

After successful registration:

1. the user is created in the database
2. a QR code is generated
3. the user is logged in automatically
4. the user is redirected to the dashboard

The QR code contains the student ID and user ID, and it is stored in the public `qrcodes` folder so it can be used later for facility scanning.

### 3.3 Logging In

A registered user enters their email and password on the login page.

Laravel's authentication system checks the credentials. If they are correct:

- the session is regenerated
- the user is redirected to the intended page, which is usually the dashboard

If authentication fails, the request is rejected and the user stays on the login page.

### 3.4 Logging Out

The logout action invalidates the session, removes cookies, regenerates the CSRF token, and redirects the user back to the login page.

This is important because the app stores session-based access and must fully clear user state when the user exits.

---

## 4. Dashboard: Main Control Center

After login, the dashboard is the main page of the app.

The dashboard displays:

- total number of facilities
- number of active reservations
- number of borrowed equipment items
- number of conflict alerts
- a list of all facilities and their current status
- a sidebar for navigation
- quick actions for profile and logout

The dashboard is rendered by a Blade view and receives data from the route closure in `routes/web.php`.

### 4.1 What the Dashboard Shows

Each facility card displays:

- facility name
- building
- current occupancy
- capacity
- status label
- status message
- occupancy percentage
- assistance button when needed

The user can click a facility card to go to the facility calendar or inspect status in more detail.

### 4.2 Live Updates

The dashboard is not static. JavaScript refreshes data automatically every 30 seconds or when the next facility transition time arrives.

This is done by calling the `/api/facilities/status` endpoint and updating the UI with the latest data.

The user sees the dashboard refresh in real time without reloading the page.

### 4.3 Dashboard Actions

From the dashboard, the user can:

- open their profile
- log out
- cancel an active reservation
- request a return for borrowed equipment
- request assistance for a facility needing access support

---

## 5. How Facility Status Is Computed

A major part of the app is the logic that decides whether a facility is open, closed, reserved, in use, or on lunch break.

### 5.1 Status Rules

The `Facility` model computes status using this order:

1. If the facility has a manual override, it uses that override.
2. If the current time is before opening time, it is **closed**.
3. If the current time is after closing time, it is **closed**.
4. If the current time is inside the lunch window, it is **lunch_break**.
5. If there is an approved reservation currently happening, it is **in_use**.
6. If there is an approved reservation in the future, it is **reserved**.
7. Otherwise, it is **open**.

### 5.2 Why This Logic Exists

This logic exists so the app can display an accurate status without requiring the admin to manually update every facility every minute.

It turns the database into a live operational model of campus facilities.

### 5.3 Assistance Requirement

A facility is marked as needing assistance when its status is either:

- `closed`
- `lunch_break`

That lets the dashboard show a help button only when the user may need extra help to access the space.

### 5.4 Next Transition Logic

The API also calculates the next time a facility will change status.

For example:

- if the facility is closed, it calculates when it will reopen
- if the facility is on lunch break, it calculates when lunch ends
- if the facility is in use, it calculates when the current reservation ends
- if the facility is reserved, it calculates when the next reservation starts

This allows the frontend to schedule the next refresh intelligently instead of polling constantly.

---

## 6. Facility Status Pages

The site has dedicated pages for key facilities such as:

- Activity Center
- Library
- Gym
- Canteen
- BAO

These pages are all based on the same facility status model and display more detailed information for each location.

The facility pages are useful because they give the user a focused view instead of forcing them to interpret the whole dashboard.

---

## 7. Reservation Flow

Reservation handling is one of the main core features of the app.

### 7.1 What Happens When a User Reserves a Facility

When a user submits a reservation, the app performs multiple checks:

1. validates that the facility exists
2. validates that the start time is after the current time
3. validates that the end time is after the start time
4. checks whether the reservation duration is over 4 hours
5. checks for conflicts with any approved reservation for that facility

If any of these checks fail, the request is rejected.

### 7.2 Pending vs Approved

Reservations are saved as **pending** first.

This means the user can request a booking, but the booking is not active until an admin approves it.

That makes the approval process explicit and prevents accidental double-booking.

### 7.3 Conflict Detection

The app checks for overlapping time windows. It rejects any reservation that overlaps with an already approved booking.

This is critical because the system is managing shared facilities and must avoid conflicting reservations.

### 7.4 Canceling a Reservation

Users can cancel reservations if the reservation is still eligible.

The cancellation logic prevents cancellation when:

- the reservation has already started too close to the current time
- the reservation is no longer in a cancellable state

This protects the system from last-minute cancellations and keeps scheduling reliable.

### 7.5 Why the System Uses Pending Approval

The app uses pending approval because it is safer than allowing all reservations to become active immediately.

A reservation is a scheduling decision that affects shared campus resources, so the admin needs a review step.

---

## 8. Equipment Borrowing Flow

The app also supports equipment borrowing.

### 8.1 Equipment Types

Users can request items such as:

- Projector
- Sound System
- Microphones
- Whiteboard

### 8.2 Borrow Request Process

When a user submits a borrow request, the system checks whether the equipment is already borrowed.

- If it is already borrowed, the request is denied.
- If it is available, the request is saved as pending.

### 8.3 Admin Approval

The admin approves borrowing requests before the item is marked as borrowed.

### 8.4 Return Requests

Once the item is borrowed, the user can submit a return request.

The admin approves the return request, and the item is marked returned.

### 8.5 Why This Design Is Useful

This design prevents lost or double-booked equipment and gives the admin control over shared resources.

---

## 9. QR Scan and Attendance Flow

The QR code system is one of the most practical parts of the app.

### 9.1 QR Generation

During registration, the system generates a QR code containing:

- the user ID
- the student ID

The QR code is stored as an SVG file in the public `qrcodes` folder.

### 9.2 QR Scanning

When a user scans the QR code at a facility, the app:

1. decodes the QR payload
2. finds the matching user
3. validates that the facility exists
4. checks whether the facility is closed
5. checks whether the user already has an open attendance log
6. either checks the user out or checks them in

### 9.3 Attendance Behavior

If the user has an open attendance log:

- the system closes the log
- the facility occupancy is decremented

If the user does not have an open attendance log:

- the system creates a new attendance log
- the facility occupancy is incremented

This gives the app a real attendance mechanism without requiring separate hardware or manual admin input.

### 9.4 Why QR Codes Were Used

QR codes offer a simple way to link a physical facility interaction to a user record in the database.

This makes it easy to track who entered a facility and how many people are inside.

---

## 10. Assistance Request Flow

The app includes an assistance feature for cases where a facility is closed or on lunch break and the user needs help.

### 10.1 How It Works

The user submits a message through the dashboard modal.

The request is saved with:

- user ID
- facility ID
- message
- status = pending

### 10.2 Admin Response

The admin can view assistance requests and mark them as resolved.

### 10.3 Why This Matters

This gives users a way to ask for help without having to hunt for a staff member manually.

---

## 11. Admin Functions

Only users marked as administrators can access the admin area.

### 11.1 Admin Dashboard

The admin dashboard allows the admin to inspect:

- users
- facilities
- pending reservations
- approved reservations
- borrowed equipment
- pending borrow requests
- return requests
- assistance requests
- attendance logs

### 11.2 Admin Actions

The admin can:

- approve or reject reservations
- approve or reject borrow requests
- approve return requests
- create new facilities
- update facility data
- delete users
- delete facilities
- grant or remove admin access
- resolve assistance requests
- cleanup old reservations and attendance logs

### 11.3 Why Admin Control Is Necessary

Campus resources are shared. The admin layer prevents unauthorized users from making changes that could affect facility availability or other users' schedules.

---

## 12. Frontend Logic and UI Behavior

The frontend is split between Blade and JavaScript.

### 12.1 Blade Views

Blade handles the server-rendered HTML structure for the pages such as:

- dashboard
- login
- registration
- facility status pages
- activity reservation page
- equipment borrowing page
- admin dashboard
- profile page

### 12.2 JavaScript Behavior

JavaScript is responsible for:

- updating the active sidebar item
- rendering progress circles
- refreshing facility status
- cancelling reservations
- submitting return requests
- handling the assistance modal

### 12.3 Tailwind CSS

Tailwind handles spacing, responsiveness, colors, component styling, and layout.

The UI uses a polished modern design with card-based panels, gradient backgrounds, and responsive mobile behavior.

---

## 13. How the Technologies Communicate

### Browser to Laravel

The browser sends HTTP requests when the user:

- opens a page
- submits a form
- clicks a button
- sends an AJAX request

### Routes

Laravel routes match the URL and send the request to the correct controller or closure.

### Controllers

Controllers validate input and call models when needed.

### Models

Models interact with the database and encapsulate logic such as facility status and cancellation rules.

### Views

Blade renders the HTML and sends it back to the browser.

### JavaScript

JavaScript receives data and updates the page dynamically.

### Database

The database stores users, facilities, reservations, borrowed equipment, attendance logs, and assistance requests.

---

## 14. File-by-File Breakdown

### 14.1 `routes/web.php`

This is the main route file for the app.

It defines:

- the dashboard route
- the activity reservation route
- reservation validation and storage routes
- facility status routes
- equipment borrowing routes
- profile routes
- mobile routes
- facilities scan and status API routes
- assistance submission route
- admin routes
- cleanup routes

This file is the main entry point for user-facing features.

### 14.2 `routes/auth.php`

This file defines the authentication routes.

It includes:

- registration
- login
- logout
- forgot password
- reset password
- email verification
- password confirmation

It separates authentication flow from the main application routes.

### 14.3 `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

This controller handles login and logout.

It is responsible for:

- displaying the login page
- authenticating users
- regenerating sessions
- clearing session data on logout

### 14.4 `app/Http/Controllers/Auth/RegisteredUserController.php`

This controller handles registration.

It is responsible for:

- showing the registration form
- validating registration input
- creating the user
- generating the QR code
- logging the user in
- redirecting to the dashboard

### 14.5 `app/Http/Controllers/FacilityController.php`

This controller handles facility-related operations.

It is responsible for:

- QR scan handling
- facility status API responses
- next transition calculation
- assistance request submission

This is the controller that turns facility data into live operational information.

### 14.6 `app/Http/Controllers/AdminController.php`

This is the central admin controller.

It handles:

- admin dashboard data loading
- facility creation and updates
- reservation approval and rejection
- borrow approval
- return approval
- cleanup actions
- assistance resolution
- user and facility management

It contains most of the admin-side business logic.

### 14.7 `app/Http/Controllers/ProfileController.php`

This controller handles profile and account management.

It is responsible for:

- showing the profile page
- updating profile information
- uploading a profile picture
- deleting the account

### 14.8 `app/Models/Facility.php`

This model represents a facility.

It is responsible for:

- computed status logic
- status message generation
- assistance requirement flags
- status label formatting
- relationship definitions to reservations and attendance logs

This is the model that gives the app its live facility intelligence.

### 14.9 `app/Models/Reservation.php`

This model represents a reservation record.

It handles:

- reservation relationships
- cancellation logic
- active reservation filtering
- pending reservation filtering

### 14.10 `app/Models/BorrowedEquipment.php`

This model represents borrowed equipment.

It handles:

- borrowing state
- return logic
- overdue checks
- borrowed and overdue scopes

### 14.11 `app/Models/AttendanceLog.php`

This model represents attendance records.

It links users and facilities and is used by the QR scan flow.

### 14.12 `app/Models/AssistanceRequest.php`

This model represents a help request.

It links the user and facility and stores the request message and status.

### 14.13 `app/Models/User.php`

This model represents the application user.

It contains relationships to reservations, borrowed equipment, and attendance logs.

### 14.14 `resources/views/dashboard.blade.php`

This is the main dashboard view.

It renders:

- facility cards
- active reservations
- borrowed equipment
- conflict alerts
- navigation
- profile information
- the assistance modal

### 14.15 `resources/views/activity_reservation.blade.php`

This is the reservation page for the Activity Center.

It lets the user view the facility and submit a reservation.

### 14.16 `resources/views/facility_status.blade.php`

This displays detailed status information for a specific facility.

### 14.17 `resources/views/equipment_borrowing.blade.php`

This page lets users request equipment and view current borrowing status.

### 14.18 `resources/views/profile/edit.blade.php`

This page lets the user update profile data and manage account details.

### 14.19 `resources/views/admin_dashboard.blade.php`

This is the admin dashboard view.

It displays all the data needed for admin review and management.

### 14.20 `public/js/dashboard_enhanced.js`

This is the main frontend JavaScript file.

It handles:

- sidebar update
- progress circle update
- live facility refresh
- cancellation actions
- return request actions

### 14.21 `resources/js/app.js`

This initializes AlpineJS for any interactive UI features that need it.

### 14.22 `resources/css/app.css`

This is the Tailwind source file that is compiled by Vite.

### 14.23 `vite.config.js`

This is the build configuration for compiling frontend assets.

### 14.24 `composer.json`

This contains PHP dependencies and scripts for Laravel.

### 14.25 `package.json`

This contains Node dependencies and scripts used for frontend asset compilation.

### 14.26 `database/seeders/DatabaseSeeder.php`

This seeds the initial admin user, test user, and facility data.

### 14.27 `database/seeders/FacilitySeeder.php`

This seeds the default facilities.

### 14.28 `database/migrations`

These migrations define the database schema for:

- users
- facilities
- reservations
- attendance logs
- borrowed equipment

---

## 15. Short Summary

UNISched is a Laravel web application for campus facility management. It allows users to register, log in, view live facility status, reserve spaces, borrow equipment, scan QR codes for attendance, and request assistance. Administrators can approve reservations, approve equipment borrowing and returns, manage facilities, resolve assistance requests, and clean up old records. The app uses Laravel for backend logic, Blade for views, Tailwind for styling, JavaScript for live updates, and a relational database for all operational data.

---

## 16. Final Takeaway

UNISched is not just a booking website. It is a complete operational system for managing campus resources in a structured and controlled way.

It combines:

- authentication
- live facility status
- reservations
- equipment management
- QR-based attendance
- admin management
- modern UI
- real-time updates

The result is a system that is practical for campus use and organized enough to scale as features are added.
