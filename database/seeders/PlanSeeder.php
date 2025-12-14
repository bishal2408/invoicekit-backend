<?php

namespace Database\Seeders;

use App\Models\ApiKey\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        Plan::insert([
            [
                'name' => 'free',
                'rate_limit_per_minute' => 60,
                'monthly_request_limit' => 1_000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'pro',
                'rate_limit_per_minute' => 300,
                'monthly_request_limit' => 50_000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'enterprise',
                'rate_limit_per_minute' => null,
                'monthly_request_limit' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
