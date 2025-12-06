<?php

namespace Database\Seeders;

use App\Models\ApiKey\ApiKeyEnvironment;
use Illuminate\Database\Seeder;

class ApiKeyEnvironmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // data to be seeeded
        $data = [
            ['name' => 'live', 'description' => 'Production environment'],
            ['name' => 'development', 'description' => 'Development environment'],
        ];

        // create or update records
        foreach ($data as $item) {
            ApiKeyEnvironment::updateOrCreate(
                ['name' => $item['name']],
                ['description' => $item['description']]
            );
        }
    }
}
