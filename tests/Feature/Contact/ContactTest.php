<?php

namespace Tests\Feature\Contact;

use App\Mail\ContactMessageMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_message_is_stored_and_email_sent(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/v1/contact', [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'subject' => 'Question sur le menu',
            'message' => 'Avez-vous des options végétariennes ?',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contact_messages', ['email' => 'jean@example.com']);

        Mail::assertQueued(ContactMessageMail::class);
    }
}   