# TODO: Make Dashboard Functional

- [x] Update DashboardController to fetch real data from database
- [x] Modify dashboard view to display dynamic data
- [x] Test dashboard functionality
- [x] Create factories for seeding data
- [x] Seed database with sample data

# TODO: Implement QR Check-in System with Timer

- [ ] Add migration for check_in_time and check_out_time fields to bookings table
- [ ] Update Booking model to include new fillable fields
- [ ] Create check-in methods in QRController (checkin, processCheckin)
- [ ] Add check-in routes to routes/admin/qr.php
- [ ] Create check-in view (resources/views/admin/qr/checkin.blade.php) with timer functionality
- [ ] Update QR scanner to include check-in option
- [ ] Add check-in navigation link to admin nav
- [ ] Test check-in functionality and timer
