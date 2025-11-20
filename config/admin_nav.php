<?php

return [
    ['label' => 'New', 'route' => 'admin.new.index', 'active' => true],
    ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => true],
    ['label' => 'Analytics & Reports', 'route' => '#', 'active' => false],

    ['label' => 'Manage Bookings', 'route' => 'admin.booking.index', 'active' => true],
    ['label' => 'Manage Reservation', 'route' => 'admin.reservation.index', 'active' => true],
    ['label' => 'Guest & Booking', 'route' => 'admin.checkin.index', 'active' => true],

    ['label' => 'Room', 'route' => 'admin.room.index', 'active' => true],
    ['label' => 'Foods', 'route' => 'admin.foods.index', 'active' => true],
    ['label' => 'Packages', 'route' => 'admin.packages.index', 'active' => true],

    ['label' => 'Payments', 'route' => 'admin.test-payment-ocr', 'active' => true],
    ['label' => 'Promos & Discounts', 'route' => '#', 'active' => false],
    ['label' => 'Point of Sale', 'route' => 'admin.pos.index', 'active' => true],

    ['label' => 'Documents & QR', 'route' => 'admin.qr.scanner', 'active' => true],
];

?>
