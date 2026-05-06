# My UNISched  Index

## Authentication
- `routes/auth.php` - login, register, password reset, email verification routes
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php` - login (`store`) and logout (`destroy`) behavior
- `resources/views/layouts/navigation.blade.php` - top navigation dropdown with user menu and logout
- `resources/views/layouts/guest.blade.php` - login/register layout
- `resources/views/layouts/app.blade.php` - main authenticated app layout

## Database and Seeding
- `database/seeders/DatabaseSeeder.php` - creates the master admin and any seed data
- `database/migrations/` - database schema and table definitions
- `app/Models/User.php` - user model, admin flag, relationships
- `app/Models/BorrowedEquipment.php` - equipment borrowing model and casts

## Routes and Controllers
- `routes/web.php` - authenticated app routes for dashboard, facility pages, reservations, borrowings, profile
- `routes/auth.php` - authentication route definitions
- `app/Http/Controllers/ProfileController.php` - profile page data, reservations, QR code access
- `app/Http/Controllers/FacilityController.php` - facility scan and check-in logic (if used)

## Reservation and Equipment
- `resources/views/activity_reservation.blade.php` - Activity Center booking page
- `resources/views/equipment_borrowing.blade.php` - borrow equipment page
- `app/Models/Reservation.php` - reservation model and rules (if present)
- `app/Models/BorrowedEquipment.php` - borrow record logic

## Dashboard and Facility Views
- `resources/views/dashboard.blade.php` - main dashboard page with cards, bookings, and equipment info
- `resources/views/facility_status.blade.php` - facility status page with reservation and attendance widgets
- `resources/views/facility_calendar.blade.php` - facility calendar view for bookings
- `resources/views/system_admin.blade.php` - admin panel pages

## Asset Loading and Vite
- `vite.config.js` - Vite build and plugin configuration
- `package.json` - Node/Vite package script definitions
- `resources/views/layouts/app.blade.php` - global app styles and scripts
- `resources/views/layouts/guest.blade.php` - login/register page assets
- `public/css/dashboard_enhanced.css` - shared app styling
- `public/js/Group2_js.js` - site client script file
- `public/js/dashboard_enhanced.js` - facility page interactions

## Common Error Locations
- SQL table errors (missing table): check migrations in `database/migrations/` and model/table names in `app/Models/`
- Missing Vite manifest: check `resources/views/layouts/*.blade.php`, `vite.config.js`, and `public/build/manifest.json`
- Logout issues: check `routes/web.php`, `routes/auth.php`, and `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- Admin behavior: check `database/seeders/DatabaseSeeder.php` for admin creation and `auth()->user()->is_admin` checks in templates

## Troubleshooting Steps
1. Check the route definitions in `routes/web.php` and `routes/auth.php`.
2. Check the corresponding Blade page in `resources/views/`.
3. Check the controller or model for the feature.
4. Check relevant migration files if the error is database-related.
5. If assets fail, verify the layout file and public asset links.

## Notes
- The Vite manifest error can be caused when `public/build/manifest.json` does not exist.
- The app currently loads CSS/JS directly from `public/css/dashboard_enhanced.css` and `public/js/Group2_js.js` instead of relying on the Vite manifest.
- The Canteen status page has attendance logs disabled because attendance tracking is not required there.
