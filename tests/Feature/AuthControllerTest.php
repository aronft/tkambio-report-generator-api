<?php

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

describe('AuthControllerTest', function () {
    it('returns a token when credentials are correct', function () {
        $user = User::factory()->create([
            'password' => env('ADMIN_PASSWORD', 'admin12345')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => env('USER_TEST_PASSWORD', 'tkambio12345')
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type']);
    });
});
