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
                'name' => 'Superior Double Room',
                'short_description' => '29m² • City view • Non-smoking • Air conditioned • Internet Access • Cable/Satellite TV • Mini Bar • En-suite Bathroom',
                'full_description' => 'Reserve a superior room with a total of 29 square-meters and it comes with 2 Double beds. It can accommodate a maximum of two guests. Experience luxury in our Superior Double Room, featuring a spacious 29 m² layout with stunning city views. Perfect for couples or small families, this room comes equipped with modern amenities and complimentary services to ensure your comfort.',
                'price' => 7000.00,
                'capacity' => 2,
                'availability' => 1,
                'bed_type' => '2 Double beds',
                'bathrooms' => 1,
                'size' => 29,
                'rate_name' => 'Published Rate',
                'image' => 'images/user/luxury-ocean-view-suite-hotel-room.jpg',
                'amenities' => json_encode(['Air conditioning', 'Free WiFi', 'Cable/Satellite TV', 'Mini Bar', 'City view', 'Non-smoking']),
                'images' => json_encode([
                    'images/user/luxury-ocean-view-suite-hotel-room.jpg',
                    'images/user/luxury-bathroom-with-bathtub.jpg',
                    'images/user/living-room-with-city-view.jpg',
                ]),
            ],
            [
                'name' => 'Deluxe Suite',
                'short_description' => '45m² • City view • Non-smoking • Air conditioned • Internet Access • Cable/Satellite TV • Mini Bar • Jacuzzi • En-suite Bathroom',
                'full_description' => 'Our most spacious suite featuring premium amenities and stunning city views. Perfect for families or those seeking extra comfort, this 45 m² suite includes a Jacuzzi and two full bathrooms for ultimate relaxation. It can accommodate a maximum of four guests with 1 King bed and 2 Single beds.',
                'price' => 9500.00,
                'capacity' => 4,
                'availability' => 2,
                'bed_type' => '1 King + 2 Singles',
                'bathrooms' => 2,
                'size' => 45,
                'rate_name' => 'Best Available Rate',
                'image' => 'images/user/beach-bungalow-bedroom.jpg',
                'amenities' => json_encode(['Air conditioning', 'Free WiFi', 'Cable/Satellite TV', 'Jacuzzi', 'City view', 'Non-smoking']),
                'images' => json_encode([
                    'images/user/beach-bungalow-bedroom.jpg',
                    'images/user/cozy-fireplace-cabin-interior.jpg',
                    'images/user/elegant-villa-bedroom.jpg',
                ]),
            ],
            [
                'name' => 'Superior King Room',
                'short_description' => '32m² • City view • Non-smoking • Air conditioned • Internet Access • Cable/Satellite TV • Mini Bar • En-suite Bathroom',
                'full_description' => 'Luxurious room with a premium King bed, perfect for couples seeking comfort and elegance. This 32 m² room features modern amenities and city views for a memorable stay. It can accommodate a maximum of two guests.',
                'price' => 8500.00,
                'capacity' => 2,
                'availability' => 1,
                'bed_type' => '1 King bed',
                'bathrooms' => 1,
                'size' => 32,
                'rate_name' => 'Published Rate',
                'image' => 'images/user/modern-tech-loft-apartment.jpg',
                'amenities' => json_encode(['Air conditioning', 'Free WiFi', 'Cable/Satellite TV', 'Mini Bar', 'City view', 'Non-smoking']),
                'images' => json_encode([
                    'images/user/modern-tech-loft-apartment.jpg',
                    'images/user/modern-apartment-kitchen.png',
                    'images/user/modern-ocean-bedroom.png',
                ]),
            ],
            [
                'name' => 'Premium Villa Suite',
                'short_description' => '75m² • Private courtyard • Non-smoking • Air conditioned • Internet Access • Full kitchen • Jacuzzi • En-suite Bathrooms',
                'full_description' => 'Our most exclusive villa suite with private courtyard and fountain, ideal for large groups or special occasions. This 75 m² villa features 3 bedrooms, 3 bathrooms, and a full kitchen for a complete home-away-from-home experience. It can accommodate a maximum of six guests.',
                'price' => 15000.00,
                'capacity' => 6,
                'availability' => 1,
                'bed_type' => '3 Bedrooms',
                'bathrooms' => 3,
                'size' => 75,
                'rate_name' => 'Published Rate',
                'image' => 'images/user/villa-courtyard-with-fountain.jpg',
                'amenities' => json_encode(['Air conditioning', 'Free WiFi', 'Full kitchen', 'Jacuzzi', 'Private courtyard', 'Non-smoking']),
                'images' => json_encode([
                    'images/user/villa-courtyard-with-fountain.jpg',
                    'images/user/historic-european-villa.jpg',
                    'images/user/historic-european-villa-exterior.jpg',
                ]),
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
