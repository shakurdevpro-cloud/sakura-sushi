<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\Auth\WelcomeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '0612345678',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token']);

        $this->assertDatabaseHas('users', ['email' => 'jean@example.com']);
        $this->assertDatabaseHas('loyalty_accounts', ['user_id' => User::first()->id]);

        Notification::assertSentTo(User::first(), WelcomeNotification::class);
    }

    public function test_user_cannot_register_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'jean@example.com']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('email');
    }
}   