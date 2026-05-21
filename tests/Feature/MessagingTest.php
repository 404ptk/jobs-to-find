<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_message_applicant()
    {
        $employer = User::factory()->create(['account_type' => 'employer']);
        $seeker = User::factory()->create(['account_type' => 'job_seeker']);
        $offer = JobOffer::factory()->create(['user_id' => $employer->id]);

        // Seeker must have an application to be messageable by employer in some logic
        Application::create([
            'user_id' => $seeker->id,
            'job_offer_id' => $offer->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($employer)->post(route('messages.store'), [
            'receiver_id' => $seeker->id,
            'content' => 'Hello from employer',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('messages', [
            'sender_id' => $employer->id,
            'receiver_id' => $seeker->id,
            'content' => 'Hello from employer',
        ]);
    }

    public function test_seeker_cannot_start_conversation_without_application()
    {
        $employer = User::factory()->create(['account_type' => 'employer']);
        $seeker = User::factory()->create(['account_type' => 'job_seeker']);

        $response = $this->actingAs($seeker)->post(route('messages.store'), [
            'receiver_id' => $employer->id,
            'content' => 'Spam from seeker',
        ]);

        // Based on Conversation 5d96f3f1 logic, seekers shouldn't start conversations
        $response->assertStatus(403);
    }
}
