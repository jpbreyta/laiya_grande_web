<?php

return [
    ['label' => 'New', 'route' => 'admin.new', 'active' => true],
    ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => true],
    ['label' => 'Analytics & Reports', 'route' => '#', 'active' => false],

    ['label' => 'Manage Bookings', 'route' => 'admin.booking', 'active' => true],
    ['label' => 'Manage Reservation', 'route' => 'admin.reservation', 'active' => true],
    ['label' => 'Guest & Booking', 'route' => 'admin.checkin', 'active' => true],

    ['label' => 'Room', 'route' => 'admin.room', 'active' => true],
    ['label' => 'Foods', 'route' => 'admin.foods', 'active' => true],
    ['label' => 'Packages', 'route' => 'admin.packages', 'active' => true],

    ['label' => 'Payments', 'route' => 'admin.test-payment-ocr', 'active' => true],
    ['label' => 'Promos & Discounts', 'route' => '#', 'active' => false],
    ['label' => 'Point of Sale', 'route' => 'admin.pos', 'active' => true],

    ['label' => 'Documents & QR', 'route' => 'admin.qr.scanner', 'active' => true],
];

?>
