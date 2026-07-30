<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout_and_token_is_revoked(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $this->assertDatabaseCount('personal_access_tokens', 1);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200);
        $this->assertDatabaseCount('personal_access_tokens', 0);

        // Force Laravel à revérifier le token en base plutôt que d'utiliser
        // l'utilisateur mis en cache par le guard sanctum lors de la requête précédente.
        Auth::forgetGuards();

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/auth/me')
            ->assertStatus(401);
    }
}