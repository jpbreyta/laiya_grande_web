<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Hermosa / Kadayawan',
                'short_description' => 'Good for 5–6 pax • ₱6,000.00 per night',
                'full_description' => 'Air-conditioned room with private CR and kitchen. Includes no entrance fee, no corkage fee, and free parking for 1 car.',
                'price' => 6000.00,
                'capacity' => 6,
                'availability' => 1,
                'has_aircon' => true,
                'has_private_cr' => true,
                'has_kitchen' => true,
                'has_free_parking' => true,
                'no_entrance_fee' => true,
                'no_corkage_fee' => true,
                'image' => 'images/rooms/HermosaKadayawan.jpg',
                'images' => [
                    'images/rooms/HermosaKadayawan.jpg',
                    'images/rooms/hermosa_kadayawan_2.jpg'
                ],
                'rate_name' => 'overnight',
            ],
            [
                'name' => 'Sigpawan Room',
                'short_description' => 'Good for 8–10 pax • ₱10,000.00 per night',
                'full_description' => 'Air-conditioned room with private CR and kitchen. Includes no entrance fee, no corkage fee, and free parking for 1 car.',
                'price' => 10000.00,
                'capacity' => 10,
                'availability' => 1,
                'has_aircon' => true,
                'has_private_cr' => true,
                'has_kitchen' => true,
                'has_free_parking' => true,
                'no_entrance_fee' => true,
                'no_corkage_fee' => true,
                'image' => 'images/rooms/Sigapawan.jpg',
                'images' => [
                    'images/rooms/Sigapawan.jpg',
                    'images/rooms/sigpawan_2.jpg'
                ],
                'rate_name' => 'overnight',
            ],
            [
                'name' => 'Dinagyang Room',
                'short_description' => 'Good for 3–4 pax • ₱5,000.00 per night',
                'full_description' => 'Air-conditioned room with private CR. Includes no entrance fee, no corkage fee, and free parking for 1 car.',
                'price' => 5000.00,
                'capacity' => 4,
                'availability' => 1,
                'has_aircon' => true,
                'has_private_cr' => true,
                'has_kitchen' => false,
                'has_free_parking' => true,
                'no_entrance_fee' => true,
                'no_corkage_fee' => true,
                'image' => 'images/rooms/dinagyang.jpg',
                'images' => [
                    'images/rooms/dinagyang_1.jpg',
                    'images/rooms/dinagyang_2.jpg'
                ],
                'rate_name' => 'overnight',
            ],
            [
                'name' => 'Aliwan Room',
                'short_description' => 'Good for 8–10 pax • ₱10,000.00 per night',
                'full_description' => 'Air-conditioned room with private CR and kitchen. Includes no entrance fee, no corkage fee, and free parking for 1 car.',
                'price' => 10000.00,
                'capacity' => 10,
                'availability' => 1,
                'has_aircon' => true,
                'has_private_cr' => true,
                'has_kitchen' => true,
                'has_free_parking' => true,
                'no_entrance_fee' => true,
                'no_corkage_fee' => true,
                'image' => 'images/rooms/Aliwan.jpg',
                'images' => [
                    'images/rooms/Aliwan.jpg',
                    'images/rooms/aliwan_2.jpg'
                ],
                'rate_name' => 'overnight',
            ],
            [
                'name' => 'Sublian Room',
                'short_description' => 'Good for 8–10 pax • ₱10,000.00 per night',
                'full_description' => 'Air-conditioned room with private CR and kitchen. Includes no entrance fee, no corkage fee, and free parking for 1 car.',
                'price' => 10000.00,
                'capacity' => 10,
                'availability' => 1,
                'has_aircon' => true,
                'has_private_cr' => true,
                'has_kitchen' => true,
                'has_free_parking' => true,
                'no_entrance_fee' => true,
                'no_corkage_fee' => true,
                'image' => 'images/rooms/Sublian.jpg',
                'images' => [
                    'images/rooms/Sublian.jpg',
                    'images/rooms/sublian_2.jpg'
                ],
                'rate_name' => 'overnight',
            ],
            [
                'name' => 'Lambayok / Pahiyas',
                'short_description' => 'Good for 8–10 pax • ₱10,000.00 per night',
                'full_description' => 'Air-conditioned room with private CR and kitchen. Includes no entrance fee, no corkage fee, and free parking for 1 car.',
                'price' => 10000.00,
                'capacity' => 10,
                'availability' => 1,
                'has_aircon' => true,
                'has_private_cr' => true,
                'has_kitchen' => true,
                'has_free_parking' => true,
                'no_entrance_fee' => true,
                'no_corkage_fee' => true,
                'image' => 'images/rooms/Pahiyas.jpg',
                'images' => [
                    'images/rooms/lambayok_pahiyas_1.jpg',
                    'images/rooms/lambayok_pahiyas_2.jpg'
                ],
                'rate_name' => 'overnight',
            ],
            [
                'name' => 'Ati-Atihan Room',
                'short_description' => 'Good for 8–10 pax • ₱10,000.00 per night',
                'full_description' => 'Air-conditioned room with private CR and kitchen. Includes no entrance fee, no corkage fee, and free parking for 1 car.',
                'price' => 10000.00,
                'capacity' => 10,
                'availability' => 1,
                'has_aircon' => true,
                'has_private_cr' => true,
                'has_kitchen' => true,
                'has_free_parking' => true,
                'no_entrance_fee' => true,
                'no_corkage_fee' => true,
                'image' => 'images/rooms/Ati-Atihan.jpg',
                'images' => [
                    'images/rooms/Ati-atihan.jpg',
                    'images/rooms/atiatihan_2.jpg'
                ],
                'rate_name' => 'overnight',
            ],
            [
                'name' => 'Moriones / Hamaka',
                'short_description' => 'Good for 8–10 pax • ₱9,000.00 per night',
                'full_description' => 'Air-conditioned room with private CR and kitchen. Includes no entrance fee, no corkage fee, and free parking for 1 car.',
                'price' => 9000.00,
                'capacity' => 10,
                'availability' => 1,
                'has_aircon' => true,
                'has_private_cr' => true,
                'has_kitchen' => true,
                'has_free_parking' => true,
                'no_entrance_fee' => true,
                'no_corkage_fee' => true,
                'image' => 'images/rooms/moriones_hamaka.jpg',
                'images' => [
                    'images/rooms/moriones_hamaka_1.jpg',
                    'images/rooms/moriones_hamaka_2.jpg'
                ],
                'rate_name' => 'overnight',
            ],
            [
                'name' => 'Pintados / Panagbenga',
                'short_description' => 'Good for 8–10 pax • ₱8,000.00 per night',
                'full_description' => 'Air-conditioned room with private CR and kitchen. Includes no entrance fee, no corkage fee, and free parking for 1 car.',
                'price' => 8000.00,
                'capacity' => 10,
                'availability' => 1,
                'has_aircon' => true,
                'has_private_cr' => true,
                'has_kitchen' => true,
                'has_free_parking' => true,
                'no_entrance_fee' => true,
                'no_corkage_fee' => true,
                'image' => 'images/rooms/Panagbenga.jpg',
                'images' => [
                    'images/rooms/pintados_panagbenga_1.jpg',
                    'images/rooms/pintados_panagbenga_2.jpg'
                ],
                'rate_name' => 'overnight',
            ],
            [
                'name' => 'Marina / Covina',
                'short_description' => 'Good for 11–15 pax • ₱15,000.00 per night',
                'full_description' => 'Air-conditioned rooms with private CR and kitchen. Includes no entrance fee, no corkage fee, free use of beachfront tables, and free parking for 1 car.',
                'price' => 15000.00,
                'capacity' => 15,
                'availability' => 1,
                'has_aircon' => true,
                'has_private_cr' => true,
                'has_kitchen' => true,
                'has_free_parking' => true,
                'no_entrance_fee' => true,
                'no_corkage_fee' => true,
                'image' => 'images/rooms/Marina (1).jpg',
                'images' => [
                    'images/rooms/marina_covina_1.jpg',
                    'images/rooms/marina_covina_2.jpg'
                ],
                'rate_name' => 'overnight',
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
