<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DefaultRolesSeeder::class,
            AdminUserSeeder::class,
            GeneralSettingsSeeder::class,
            CommunicationSettingsSeeder::class,
            ContactSubjectSeeder::class,
            RoomSeeder::class,
            FoodCategorySeeder::class,
            FoodSeeder::class,
            RentalItemSeeder::class,
            WaterSportsSeeder::class,
            TourPackagesSeeder::class,
            TermsAndConditionsSeeder::class,
            HouseRulesSeeder::class,
            ExampleBookingsReservationsSeeder::class,
            PopulateReservationNumbersSeeder::class,
        ]);

        if (filter_var(
            env('SEED_HISTORICAL_DATA', false),
            FILTER_VALIDATE_BOOL
        )) {
            $this->call(HistoricalDataSeeder::class);
        }

        $this->call([
            GuestStaySeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
