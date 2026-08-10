<?php

namespace Tests\Feature\Newsletter;

use App\Jobs\SendNewsletterConfirmationJob;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_subscribe_to_newsletter(): void
    {
        Queue::fake();

        $response = $this->postJson('/api/v1/newsletter/subscribe', [
            'email' => 'jean@example.com',
            'name' => 'Jean Dupont',
            'source' => 'homepage',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('subscribers', ['email' => 'jean@example.com']);

        Queue::assertPushed(SendNewsletterConfirmationJob::class);
    }

    public function test_double_opt_in_confirmation_flow_works(): void
    {
        $subscriber = Subscriber::factory()->create();

        $this->assertNull($subscriber->confirmed_at);

        $response = $this->getJson("/api/v1/newsletter/confirm/{$subscriber->token}");

        $response->assertStatus(200);
        $this->assertNotNull($subscriber->fresh()->confirmed_at);
    }

    public function test_unsubscribe_token_invalidates_subscription(): void
    {
        $subscriber = Subscriber::factory()->confirmed()->create();

        $response = $this->getJson("/api/v1/newsletter/unsubscribe/{$subscriber->token}");

        $response->assertStatus(200);
        $this->assertNotNull($subscriber->fresh()->unsubscribed_at);
    }
}