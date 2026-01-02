<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory(1000)->withValidBirthDate()->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => env('USER_TEST_EMAIL', 'user@tkambio.com'),
            'password' => env('USER_TEST_PASSWORD', 'admin12345')
        ]);
    }
}
