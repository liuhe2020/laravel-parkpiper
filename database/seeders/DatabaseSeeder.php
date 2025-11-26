<?php

namespace Database\Seeders;

use App\Models\Permit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 100 permits with UK license plates
        // Durations: 1, 3, 7, 30, 90, 180, or 365 days

        // 60 currently valid permits
        Permit::factory()
            ->count(60)
            ->currentlyValid()
            ->create();

        // 25 expired permits
        Permit::factory()
            ->count(25)
            ->expired()
            ->create();

        // 15 future permits
        Permit::factory()
            ->count(15)
            ->future()
            ->create();

        $this->command->info('✅ Created 100 permits with UK license plates!');
        $this->command->info('📊 Breakdown: 60 active, 25 expired, 15 future');
        $this->command->info('⏱️ Durations: 1, 3, 7, 30, 90, 180, or 365 days');
    }
}
