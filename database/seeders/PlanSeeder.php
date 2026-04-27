<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(
            ['slug' => 'basic'],
            [
                'name' => 'Basic Plan',
                'price' => 29.99,
                'driver_limit' => 10,
                'description' => 'Basic plan allows up to 10 drivers',
                'is_active' => true,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'premium'],
            [
                'name' => 'Premium Plan',
                'price' => 79.99,
                'driver_limit' => null, // unlimited
                'description' => 'Unlimited drivers with premium support',
                'is_active' => true,
            ]
        );
    }
}