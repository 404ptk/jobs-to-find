<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Company;
use App\Models\JobOffer;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobOfferTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_offer_belongs_to_user()
    {
        $user = User::factory()->create();
        $offer = JobOffer::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $offer->user);
        $this->assertEquals($user->id, $offer->user->id);
    }

    public function test_job_offer_belongs_to_category()
    {
        $category = Category::create(['name' => 'IT', 'slug' => 'it']);
        $offer = JobOffer::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $offer->category);
        $this->assertEquals($category->id, $offer->category->id);
    }

    public function test_job_offer_belongs_to_location()
    {
        $location = Location::create(['country' => 'PL', 'city' => 'Warsaw', 'address' => 'Test']);
        $offer = JobOffer::factory()->create(['location_id' => $location->id]);

        $this->assertInstanceOf(Location::class, $offer->location);
        $this->assertEquals($location->id, $offer->location->id);
    }

    public function test_job_offer_belongs_to_company()
    {
        $user = User::factory()->create();
        $company = Company::create([
            'user_id' => $user->id,
            'name' => 'Test Co',
            'description' => 'Desc',
            'location' => 'Warsaw',
            'nip' => '1234567890',
        ]);
        $offer = JobOffer::factory()->create(['company_id' => $company->id]);

        $this->assertInstanceOf(Company::class, $offer->company);
    }
}
