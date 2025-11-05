<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Room;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkIn = $this->faker->dateTimeBetween('now', '+30 days');
        $checkOut = $this->faker->dateTimeBetween($checkIn, $checkIn->format('Y-m-d H:i:s') . ' +7 days');

        return [
            'room_id' => Room::factory(),
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->numberBetween(9000000000, 9999999999),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'number_of_guests' => $this->faker->numberBetween(1, 4),
            'special_request' => $this->faker->optional()->sentence(),
            'payment_method' => $this->faker->randomElement(['gcash', 'paymaya', 'bank_transfer']),
            'payment' => $this->faker->randomElement(['full', 'partial']),
            'total_price' => $this->faker->numberBetween(5000, 25000),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'reservation_number' => 'BK-' . $this->faker->unique()->numberBetween(100000, 999999),
        ];
    }
}
