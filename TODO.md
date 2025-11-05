# TODO: Differentiate Booking and Reservation Codes

- [x] Modify `generateReservationNumber()` in `app/Http/Controllers/user/BookingController.php` to use 'BK-' prefix instead of 'RSV-'.
- [x] Confirm `generateReservationNumber()` in `app/Http/Controllers/user/ReservationController.php` uses 'RSV-' prefix (already correct).
- [x] Update `PopulateReservationNumbersSeeder.php` to generate 'BK-' for bookings and 'RSV-' for reservations.
