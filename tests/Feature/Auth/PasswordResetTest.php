<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reset_password_end_to_end(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword123'),
        ]);

        $token = Password::createToken($user);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200);

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));

        // L'ancien mot de passe ne doit plus fonctionner
        $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'oldpassword123',
        ])->assertStatus(401);

        // Le nouveau mot de passe doit fonctionner
        $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'newpassword123',
        ])->assertStatus(200);
    }
}