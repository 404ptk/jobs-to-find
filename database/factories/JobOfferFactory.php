<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobOffer>
 */
class JobOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $employmentTypes = ['full-time', 'part-time', 'contract', 'freelance', 'internship'];
        $salaryMin = fake()->numberBetween(3000, 8000);
        $salaryMax = $salaryMin + fake()->numberBetween(2000, 10000);

        return [
            'user_id' => User::factory(), // This will be improved in seeder to use existing employers
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraph(3),
            'requirements' => fake()->paragraph(2),
            'company_name' => fake()->company(),
            'salary_min' => $salaryMin,
            'salary_max' => $salaryMax,
            'currency' => 'PLN', // Default currency
            'employment_type' => fake()->randomElement($employmentTypes),
            'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            'location_id' => Location::inRandomOrder()->first()->id ?? 1,
            'is_active' => true,
            'is_approved' => true,
            'expires_at' => fake()->dateTimeBetween('now', '+3 months'),
        ];
    }
}
