<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Models\Company;
use App\Models\JobOffer;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_job_offers()
    {
        $user = User::factory()->create();
        $offer = JobOffer::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->jobOffers->contains($offer));
        $this->assertInstanceOf(JobOffer::class, $user->jobOffers->first());
    }

    public function test_user_has_skills()
    {
        $user = User::factory()->create();
        $skill = Skill::create(['name' => 'PHP']);
        $user->skills()->attach($skill);

        $this->assertTrue($user->skills->contains($skill));
    }

    public function test_user_has_applications()
    {
        $user = User::factory()->create();
        $offer = JobOffer::factory()->create();
        $application = Application::create([
            'user_id' => $user->id,
            'job_offer_id' => $offer->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'status' => 'pending',
            'cv_path' => 'test.pdf',
        ]);

        $this->assertTrue($user->applications->contains($application));
    }

    public function test_user_has_companies()
    {
        $user = User::factory()->create();
        $company = Company::create([
            'user_id' => $user->id,
            'name' => 'Test Company',
            'description' => 'Test Desc',
            'location' => 'Warsaw',
            'nip' => '1234567890',
        ]);

        $this->assertTrue($user->companies->contains($company));
    }

    public function test_user_has_favorite_offers()
    {
        $user = User::factory()->create();
        $offer = JobOffer::factory()->create();
        $user->favoriteOffers()->attach($offer);

        $this->assertTrue($user->favoriteOffers->contains($offer));
    }

    public function test_is_field_visible_logic()
    {
        $user = User::factory()->create([
            'privacy_settings' => [
                'email' => false,
                'phone' => true,
            ],
        ]);

        $this->assertFalse($user->isFieldVisible('email'));
        $this->assertTrue($user->isFieldVisible('phone'));
        $this->assertTrue($user->isFieldVisible('non_existent_field')); // Default should be true
    }
}
