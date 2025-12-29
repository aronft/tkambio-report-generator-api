<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('User seeder', function () {
    it('generate 1k users', function () {
        $this->seed(DatabaseSeeder::class);

        expect(User::count())->toBe(1000);
    });
});
