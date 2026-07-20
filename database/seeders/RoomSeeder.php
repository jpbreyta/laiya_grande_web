<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $amenityIds = $this->seedAmenities();

            foreach ($this->rooms() as $roomData) {
                $rate = $roomData['rate'];
                $images = $roomData['images'];
                $amenities = $roomData['amenities'];

                unset(
                    $roomData['rate'],
                    $roomData['images'],
                    $roomData['amenities']
                );

                DB::table('rooms')->upsert(
                    [[
                        ...$roomData,
                        'deleted_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]],
                    ['name'],
                    [
                        'code',
                        'short_description',
                        'full_description',
                        'capacity',
                        'inventory_count',
                        'status',
                        'deleted_at',
                        'updated_at',
                    ]
                );

                $roomId = DB::table('rooms')
                    ->where('name', $roomData['name'])
                    ->value('id');

                if ($roomId === null) {
                    throw new RuntimeException(
                        "Unable to resolve seeded room '{$roomData['name']}'."
                    );
                }

                DB::table('room_rates')->upsert(
                    [[
                        'room_id' => $roomId,
                        'rate_type' => 'overnight',
                        'name' => 'Standard Overnight',
                        'price' => $rate,
                        'minimum_nights' => 1,
                        'starts_on' => null,
                        'ends_on' => null,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]],
                    ['room_id', 'rate_type', 'name'],
                    [
                        'price',
                        'minimum_nights',
                        'starts_on',
                        'ends_on',
                        'is_active',
                        'updated_at',
                    ]
                );

                DB::table('room_images')
                    ->where('room_id', $roomId)
                    ->update([
                        'is_primary' => false,
                        'updated_at' => now(),
                    ]);

                foreach ($images as $index => $path) {
                    DB::table('room_images')->updateOrInsert(
                        [
                            'room_id' => $roomId,
                            'path' => $path,
                        ],
                        [
                            'alt_text' => $roomData['name'],
                            'sort_order' => $index,
                            'is_primary' => $index === 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                foreach ($amenities as $amenityName) {
                    $amenityId = $amenityIds[$amenityName] ?? null;

                    if ($amenityId === null) {
                        throw new RuntimeException(
                            "Amenity '{$amenityName}' was not seeded."
                        );
                    }

                    DB::table('amenity_room')->updateOrInsert(
                        [
                            'amenity_id' => $amenityId,
                            'room_id' => $roomId,
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        });
    }

    private function seedAmenities(): array
    {
        $amenities = [
            ['name' => 'Air Conditioning', 'icon' => 'fa-solid fa-snowflake'],
            ['name' => 'Private Bathroom', 'icon' => 'fa-solid fa-bath'],
            ['name' => 'Kitchen', 'icon' => 'fa-solid fa-kitchen-set'],
            ['name' => 'Free Parking', 'icon' => 'fa-solid fa-square-parking'],
            ['name' => 'No Entrance Fee', 'icon' => 'fa-solid fa-ticket'],
            ['name' => 'No Corkage Fee', 'icon' => 'fa-solid fa-bottle-water'],
            ['name' => 'Beachfront Tables', 'icon' => 'fa-solid fa-umbrella-beach'],
        ];

        foreach ($amenities as $amenity) {
            DB::table('amenities')->updateOrInsert(
                ['name' => $amenity['name']],
                [
                    'description' => null,
                    'icon' => $amenity['icon'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return DB::table('amenities')
            ->whereIn('name', array_column($amenities, 'name'))
            ->pluck('id', 'name')
            ->map(static fn ($id): int => (int) $id)
            ->all();
    }

    private function rooms(): array
    {
        $standardAmenities = [
            'Air Conditioning',
            'Private Bathroom',
            'Free Parking',
            'No Entrance Fee',
            'No Corkage Fee',
        ];

        $kitchenAmenities = [
            ...$standardAmenities,
            'Kitchen',
        ];

        return [
            [
                'code' => 'ROOM-HERMOSA-KADAYAWAN',
                'name' => 'Hermosa / Kadayawan',
                'short_description' => 'Good for 5 to 6 guests.',
                'full_description' => 'Air-conditioned room with a private bathroom and kitchen. Includes free parking for one vehicle.',
                'capacity' => 6,
                'inventory_count' => 1,
                'status' => 'available',
                'rate' => 6000.00,
                'images' => [
                    'images/rooms/HermosaKadayawan.jpg',
                    'images/rooms/hermosa_kadayawan_2.jpg',
                ],
                'amenities' => $kitchenAmenities,
            ],
            [
                'code' => 'ROOM-SIGPAWAN',
                'name' => 'Sigpawan Room',
                'short_description' => 'Good for 8 to 10 guests.',
                'full_description' => 'Air-conditioned room with a private bathroom and kitchen. Includes free parking for one vehicle.',
                'capacity' => 10,
                'inventory_count' => 1,
                'status' => 'available',
                'rate' => 10000.00,
                'images' => [
                    'images/rooms/Sigapawan.jpg',
                    'images/rooms/sigpawan_2.jpg',
                ],
                'amenities' => $kitchenAmenities,
            ],
            [
                'code' => 'ROOM-DINAGYANG',
                'name' => 'Dinagyang Room',
                'short_description' => 'Good for 3 to 4 guests.',
                'full_description' => 'Air-conditioned room with a private bathroom. Includes free parking for one vehicle.',
                'capacity' => 4,
                'inventory_count' => 1,
                'status' => 'available',
                'rate' => 5000.00,
                'images' => [
                    'images/rooms/dinagyang_1.jpg',
                    'images/rooms/dinagyang_2.jpg',
                ],
                'amenities' => $standardAmenities,
            ],
            [
                'code' => 'ROOM-ALIWAN',
                'name' => 'Aliwan Room',
                'short_description' => 'Good for 8 to 10 guests.',
                'full_description' => 'Air-conditioned room with a private bathroom and kitchen. Includes free parking for one vehicle.',
                'capacity' => 10,
                'inventory_count' => 1,
                'status' => 'available',
                'rate' => 10000.00,
                'images' => [
                    'images/rooms/Aliwan.jpg',
                    'images/rooms/aliwan_2.jpg',
                ],
                'amenities' => $kitchenAmenities,
            ],
            [
                'code' => 'ROOM-SUBLIAN',
                'name' => 'Sublian Room',
                'short_description' => 'Good for 8 to 10 guests.',
                'full_description' => 'Air-conditioned room with a private bathroom and kitchen. Includes free parking for one vehicle.',
                'capacity' => 10,
                'inventory_count' => 1,
                'status' => 'available',
                'rate' => 10000.00,
                'images' => [
                    'images/rooms/Sublian.jpg',
                    'images/rooms/sublian_2.jpg',
                ],
                'amenities' => $kitchenAmenities,
            ],
            [
                'code' => 'ROOM-LAMBAYOK-PAHIYAS',
                'name' => 'Lambayok / Pahiyas',
                'short_description' => 'Good for 8 to 10 guests.',
                'full_description' => 'Air-conditioned room with a private bathroom and kitchen. Includes free parking for one vehicle.',
                'capacity' => 10,
                'inventory_count' => 1,
                'status' => 'available',
                'rate' => 10000.00,
                'images' => [
                    'images/rooms/lambayok_pahiyas_1.jpg',
                    'images/rooms/lambayok_pahiyas_2.jpg',
                ],
                'amenities' => $kitchenAmenities,
            ],
            [
                'code' => 'ROOM-ATI-ATIHAN',
                'name' => 'Ati-Atihan Room',
                'short_description' => 'Good for 8 to 10 guests.',
                'full_description' => 'Air-conditioned room with a private bathroom and kitchen. Includes free parking for one vehicle.',
                'capacity' => 10,
                'inventory_count' => 1,
                'status' => 'available',
                'rate' => 10000.00,
                'images' => [
                    'images/rooms/Ati-atihan.jpg',
                    'images/rooms/atiatihan_2.jpg',
                ],
                'amenities' => $kitchenAmenities,
            ],
            [
                'code' => 'ROOM-MORIONES-HAMAKA',
                'name' => 'Moriones / Hamaka',
                'short_description' => 'Good for 8 to 10 guests.',
                'full_description' => 'Air-conditioned room with a private bathroom and kitchen. Includes free parking for one vehicle.',
                'capacity' => 10,
                'inventory_count' => 1,
                'status' => 'available',
                'rate' => 9000.00,
                'images' => [
                    'images/rooms/moriones_hamaka_1.jpg',
                    'images/rooms/moriones_hamaka_2.jpg',
                ],
                'amenities' => $kitchenAmenities,
            ],
            [
                'code' => 'ROOM-PINTADOS-PANAGBENGA',
                'name' => 'Pintados / Panagbenga',
                'short_description' => 'Good for 8 to 10 guests.',
                'full_description' => 'Air-conditioned room with a private bathroom and kitchen. Includes free parking for one vehicle.',
                'capacity' => 10,
                'inventory_count' => 1,
                'status' => 'available',
                'rate' => 8000.00,
                'images' => [
                    'images/rooms/pintados_panagbenga_1.jpg',
                    'images/rooms/pintados_panagbenga_2.jpg',
                ],
                'amenities' => $kitchenAmenities,
            ],
            [
                'code' => 'ROOM-MARINA-COVINA',
                'name' => 'Marina / Covina',
                'short_description' => 'Good for 11 to 15 guests.',
                'full_description' => 'Air-conditioned rooms with private bathrooms and a kitchen. Includes beachfront tables and free parking for one vehicle.',
                'capacity' => 15,
                'inventory_count' => 1,
                'status' => 'available',
                'rate' => 15000.00,
                'images' => [
                    'images/rooms/marina_covina_1.jpg',
                    'images/rooms/marina_covina_2.jpg',
                ],
                'amenities' => [
                    ...$kitchenAmenities,
                    'Beachfront Tables',
                ],
            ],
        ];
    }
}
