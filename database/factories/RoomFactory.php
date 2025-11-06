<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Deluxe Suite', 'Ocean View Room', 'Garden Villa', 'Mountain Cabin', 'Beach Bungalow']),
            'short_description' => $this->faker->sentence(10),
            'full_description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->numberBetween(5000, 25000),
            'capacity' => $this->faker->numberBetween(1, 6),
            'availability' => $this->faker->boolean(80), // 80% chance of being available
            'bed_type' => $this->faker->randomElement(['King', 'Queen', 'Twin', 'Double']),
            'bathrooms' => $this->faker->numberBetween(1, 2),
            'size' => $this->faker->numberBetween(20, 100),
            'amenities' => $this->faker->randomElements(['WiFi', 'Air Conditioning', 'Mini Bar', 'Balcony', 'Kitchen', 'TV', 'Safe'], $this->faker->numberBetween(3, 6)),
            'images' => [$this->faker->imageUrl(800, 600, 'room')],
            'rate_name' => $this->faker->randomElement(['Standard Rate', 'Premium Rate', 'Seasonal Rate']),
            'image' => $this->faker->imageUrl(800, 600, 'room'),
        ];
    }
}
