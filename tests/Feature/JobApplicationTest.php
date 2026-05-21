<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_seeker_can_apply_for_job()
    {
        Storage::fake('public');

        $seeker = User::factory()->create(['account_type' => 'job_seeker']);
        $offer = JobOffer::factory()->create();

        $response = $this->actingAs($seeker)->post(route('job-offers.apply', $offer->id), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'cv' => UploadedFile::fake()->create('cv.pdf', 100),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('applications', [
            'user_id' => $seeker->id,
            'job_offer_id' => $offer->id,
            'email' => 'john@example.com',
        ]);
    }

    public function test_job_seeker_cannot_apply_twice_for_same_job()
    {
        $seeker = User::factory()->create(['account_type' => 'job_seeker']);
        $offer = JobOffer::factory()->create();

        Application::create([
            'user_id' => $seeker->id,
            'job_offer_id' => $offer->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($seeker)->post(route('job-offers.apply', $offer->id), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'cv' => UploadedFile::fake()->create('cv.pdf', 100),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_employer_can_accept_application()
    {
        $employer = User::factory()->create(['account_type' => 'employer']);
        $offer = JobOffer::factory()->create(['user_id' => $employer->id]);
        $seeker = User::factory()->create();

        $application = Application::create([
            'user_id' => $seeker->id,
            'job_offer_id' => $offer->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($employer)->post(route('application.accept', $application->id));

        $response->assertStatus(302);
        $this->assertEquals('accepted', $application->fresh()->status);
    }
}
