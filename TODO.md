# Notification System Fix TODO

## Tasks
- [x] Add markAllAsRead method to NotificationController
- [x] Add route for /mark-all-read
- [x] Add "Mark all as read" button to notification dropdown in admin layouts
- [x] Add notification creation in BookingController::confirmBooking
- [x] Add notification creation in ReservationController::store
- [x] Update notification click handler to redirect based on type and mark as read

## Files to Edit
- app/Http/Controllers/Admin/NotificationController.php
- routes/admin/notifications.php
- resources/views/admin/layouts/app.blade.php
- resources/views/admin/inbox/layouts/app.blade.php
- app/Http/Controllers/user/BookingController.php
- app/Http/Controllers/user/ReservationController.php

## Testing
- [ ] Test notification creation for bookings/reservations
- [ ] Test mark all as read functionality
- [ ] Test redirects on notification click
- [ ] Ensure notifications are marked as read after redirect
