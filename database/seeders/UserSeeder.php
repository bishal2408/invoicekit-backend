<?php

namespace Database\Seeders;

use App\Models\User\User;
use App\Traits\PasswordTrait;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use PasswordTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'invoicekit',
            'email' => 'invoicekit@example.com',
        ]);

        $password = 'password';
        $timestamp = strtotime($admin->created_at);
        $hash = $this->generatePasswordHash($admin->id, $timestamp, $password);
        $admin->update([
            'password' => $hash,
        ]);
    }
}
