<?php

namespace Tests\Feature\Review;

use App\Models\Review;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_submit_review(): void
    {
        $response = $this->postJson('/api/v1/reviews', [
            'rating' => 5,
            'title' => 'Excellent',
            'body' => 'Les sushis étaient délicieux, service impeccable.',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('reviews', ['title' => 'Excellent', 'status' => 'pending']);
    }

    public function test_review_is_not_public_before_approval(): void
    {
        $pending = Review::factory()->create(['title' => 'Avis en attente']);
        $approved = Review::factory()->approved()->create(['title' => 'Avis approuvé']);

        $response = $this->getJson('/api/v1/reviews');

        $response->assertStatus(200);
        $titles = collect($response->json('data'))->pluck('title');

        $this->assertFalse($titles->contains('Avis en attente'));
        $this->assertTrue($titles->contains('Avis approuvé'));
    }

    public function test_admin_can_approve_review(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $review = Review::factory()->create();

        $response = $this->actingAs($admin)->patch(route('admin.reviews.approve', $review));

        $response->assertRedirect();
        $this->assertSame('approved', $review->fresh()->status->value);
        $this->assertNotNull($review->fresh()->approved_at);
    }
}