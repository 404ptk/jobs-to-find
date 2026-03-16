<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;

class JobOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('job_offers')->truncate();
        DB::table('job_offer_skill')->truncate();
        $employer = User::where('username', 'employer_anna')->first();
        $employerTom = User::where('username', 'employer_tom')->first();

        if (!$employer || !$employerTom) {
            $this->command->error('Users not found. Please run UserSeeder first.');
            return;
        }

        $annaCompany = $employer->companies()->first() ?: Company::create([
            'user_id' => $employer->id,
            'name' => 'TechVision Solutions',
            'description' => fake()->paragraph(3),
            'location_id' => \App\Models\Location::query()->value('id'),
            'founded_at' => fake()->numberBetween(1980, (int) now()->format('Y')),
            'nip' => (string) fake()->numerify('##########'),
        ]);

        $tomCompany = $employerTom->companies()->first() ?: Company::create([
            'user_id' => $employerTom->id,
            'name' => 'CloudTech Innovations',
            'description' => fake()->paragraph(3),
            'location_id' => \App\Models\Location::query()->value('id'),
            'founded_at' => fake()->numberBetween(1980, (int) now()->format('Y')),
            'nip' => (string) fake()->numerify('##########'),
        ]);

        $allSkills = \App\Models\Skill::all();
        $allParentCategories = \App\Models\Category::isParent()->get();
        $allSubCategories = \App\Models\Category::isChild()->get();
        $allLocations = \App\Models\Location::all();

        $remoteId = \App\Models\Location::where('city', 'Remote')->first()->id;
        $warsawId = \App\Models\Location::where('city', 'Warsaw')->where('country', 'Poland')->first()->id;
        $krakowId = \App\Models\Location::where('city', 'Krakow')->where('country', 'Poland')->first()->id;

        $fullStackId = \App\Models\Category::where('name', 'Full Stack Developer')->first()->id;
        $frontendId = \App\Models\Category::where('name', 'Frontend Developer')->first()->id;
        $contentCreatorId = \App\Models\Category::where('name', 'Content Creator')->first()->id;
        $devOpsId = \App\Models\Category::where('name', 'DevOps Engineer')->first()->id;
        $uiuxId = \App\Models\Category::where('name', 'UI/UX Designer')->first()->id;

        if ($allSkills->isEmpty() || $allParentCategories->isEmpty() || $allSubCategories->isEmpty() || $allLocations->isEmpty()) {
            $this->command->error('Resources not found. Please run SkillSeeder, CategorySeeder, and LocationSeeder first.');
            return;
        }

        $jobOffers = [
            [
                'data' => [
                    'user_id' => $employer->id,
                    'title' => 'Senior Full Stack Developer',
                    'description' => 'We are looking for an experienced Full Stack Developer to join our growing team. You will be responsible for developing and maintaining web applications using modern technologies. This is a great opportunity to work on exciting projects with a talented team in a dynamic environment.',
                    'requirements' => '- 5+ years of experience in web development
                - Strong knowledge of PHP, Laravel, JavaScript, React
                - Experience with RESTful APIs and microservices
                - Knowledge of SQL and NoSQL databases
                - Excellent problem-solving skills
                - Good communication skills in English',
                    'company_name' => 'TechVision Solutions',
                    'salary_min' => 12000,
                    'salary_max' => 18000,
                    'currency' => 'EUR',
                    'employment_type' => 'full-time',
                    'category_id' => $fullStackId,
                    'location_id' => $warsawId,
                    'is_active' => true,
                    'is_approved' => true,
                    'expires_at' => Carbon::now()->addMonths(2),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                'skills' => ['PHP', 'Laravel', 'JavaScript', 'React', 'SQL']
            ],
            [
                'data' => [
                    'user_id' => $employer->id,
                    'title' => 'Junior Frontend Developer',
                    'description' => 'Join our team as a Junior Frontend Developer! This is an excellent opportunity for someone starting their career in web development. You will work on creating responsive and user-friendly interfaces while learning from experienced developers.',
                    'requirements' => '- 1+ year of experience with HTML, CSS, JavaScript
                - Basic knowledge of React or Vue.js
                - Understanding of responsive design
                - Willingness to learn and grow
                - Team player attitude
                - English level: B2 or higher',
                    'company_name' => 'TechVision Solutions',
                    'salary_min' => 5000,
                    'salary_max' => 7000,
                    'currency' => 'EUR',
                    'employment_type' => 'full-time',
                    'category_id' => $frontendId,
                    'location_id' => $remoteId,
                    'is_active' => true,
                    'is_approved' => true,
                    'expires_at' => Carbon::now()->addMonths(1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                'skills' => ['HTML', 'CSS', 'JavaScript', 'React']
            ],
            [
                'data' => [
                    'user_id' => $employer->id,
                    'title' => 'Marketing Specialist - Internship',
                    'description' => 'We offer an internship position for students or recent graduates interested in digital marketing. You will have the opportunity to work on real marketing campaigns, create content, and analyze market trends under the guidance of experienced marketers.',
                    'requirements' => '- Currently studying Marketing, Business, or related field
                - Basic knowledge of social media platforms
                - Creative thinking and good writing skills
                - Proficiency in MS Office
                - Eagerness to learn digital marketing tools
                - Good English communication skills',
                    'company_name' => 'TechVision Solutions',
                    'salary_min' => 3000,
                    'salary_max' => 4000,
                    'currency' => 'EUR',
                    'employment_type' => 'internship',
                    'category_id' => $contentCreatorId,
                    'location_id' => $krakowId,
                    'is_active' => true,
                    'is_approved' => true,
                    'expires_at' => Carbon::now()->addMonths(1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                'skills' => ['Communication', 'Teamwork', 'Creativity']
            ],
        ];

        $pendingOffers = [
            [
                'data' => [
                    'user_id' => $employerTom->id,
                    'title' => 'DevOps Engineer',
                    'description' => 'We are seeking a talented DevOps Engineer to join our innovative startup. You will be responsible for building and maintaining our cloud infrastructure, implementing CI/CD pipelines, and ensuring system reliability and scalability.',
                    'requirements' => '- 3+ years of experience in DevOps/SRE
                - Strong knowledge of AWS or Azure
                - Experience with Docker and Kubernetes
                - Proficiency in scripting (Python, Bash)
                - Experience with CI/CD tools (Jenkins, GitLab CI)
                - Understanding of monitoring and logging systems',
                    'company_name' => 'CloudTech Innovations',
                    'salary_min' => 8000,
                    'salary_max' => 12000,
                    'currency' => 'EUR',
                    'employment_type' => 'full-time',
                    'category_id' => $devOpsId,
                    'location_id' => $remoteId,
                    'is_active' => true,
                    'is_approved' => false,
                    'expires_at' => Carbon::now()->addMonths(2),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                'skills' => ['Docker', 'Kubernetes', 'AWS', 'Linux', 'Jenkins']
            ],
            [
                'data' => [
                    'user_id' => $employerTom->id,
                    'title' => 'UI/UX Designer',
                    'description' => 'Join our creative team as a UI/UX Designer! You will work on designing beautiful and intuitive user interfaces for our web and mobile applications. This is a great opportunity to shape the user experience of cutting-edge products.',
                    'requirements' => '- 2+ years of experience in UI/UX design
                - Proficiency in Figma or Adobe XD
                - Strong portfolio showcasing web/mobile designs
                - Understanding of user-centered design principles
                - Knowledge of HTML/CSS is a plus
                - Excellent communication and collaboration skills',
                    'company_name' => 'CloudTech Innovations',
                    'salary_min' => 6000,
                    'salary_max' => 9000,
                    'currency' => 'EUR',
                    'employment_type' => 'full-time',
                    'category_id' => $uiuxId,
                    'location_id' => $remoteId,
                    'is_active' => true,
                    'is_approved' => false,
                    'expires_at' => Carbon::now()->addMonths(1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                'skills' => ['UI/UX Design', 'Figma', 'Adobe XD', 'Photoshop']
            ],
        ];

        foreach ($jobOffers as $offerData) {
            $offerData['data']['company_id'] = $annaCompany->id;
            $offerData['data']['company_name'] = $annaCompany->name;
            $offer = \App\Models\JobOffer::create($offerData['data']);
            $skillIds = $allSkills->whereIn('name', $offerData['skills'])->pluck('id');
            $offer->skills()->attach($skillIds);
        }

        foreach ($pendingOffers as $offerData) {
            $offerData['data']['company_id'] = $tomCompany->id;
            $offerData['data']['company_name'] = $tomCompany->name;
            $offer = \App\Models\JobOffer::create($offerData['data']);
            $skillIds = $allSkills->whereIn('name', $offerData['skills'])->pluck('id');
            $offer->skills()->attach($skillIds);
        }

        $this->command->info('Job offers created successfully for employer_anna and employer_tom!');

        $employers = User::where('account_type', 'employer')->get();

        if ($employers->count() > 0) {
            foreach ($employers as $emp) {
                $employerCompany = $emp->companies()->inRandomOrder()->first() ?: Company::create([
                    'user_id' => $emp->id,
                    'name' => fake()->company(),
                    'description' => fake()->paragraph(3),
                    'location_id' => $allLocations->random()->id,
                    'founded_at' => fake()->numberBetween(1980, (int) now()->format('Y')),
                    'nip' => (string) fake()->numerify('##########'),
                ]);

                $offers = \App\Models\JobOffer::factory()->count(rand(2, 5))->create([
                    'user_id' => $emp->id,
                    'company_id' => $employerCompany->id,
                    'company_name' => $employerCompany->name,
                    'category_id' => $allSubCategories->random()->id,
                    'location_id' => $allLocations->random()->id,
                    'is_approved' => true,
                ]);

                foreach ($offers as $offer) {
                    if (rand(1, 10) > 3) {
                        $offer->skills()->attach($allSkills->random(rand(2, 6))->pluck('id'));
                    }
                }

                $pending = \App\Models\JobOffer::factory()->count(rand(0, 2))->create([
                    'user_id' => $emp->id,
                    'company_id' => $employerCompany->id,
                    'company_name' => $employerCompany->name,
                    'category_id' => $allSubCategories->random()->id,
                    'location_id' => $allLocations->random()->id,
                    'is_approved' => false,
                ]);

                foreach ($pending as $offer) {
                    if (rand(1, 10) > 4) {
                        $offer->skills()->attach($allSkills->random(rand(1, 4))->pluck('id'));
                    }
                }
            }
            $this->command->info('Additional job offers generated for other employers.');
        }

        $allEmployers = User::where('account_type', 'employer')->get();

        if ($allEmployers->count() > 0) {
            for ($i = 0; $i < 30; $i++) {
                $randomDate = now()->subMonths(rand(1, 11))->subDays(rand(0, 27));
                $emp = $allEmployers->random();
                $employerCompany = $emp->companies()->inRandomOrder()->first() ?: Company::create([
                    'user_id' => $emp->id,
                    'name' => fake()->company(),
                    'description' => fake()->paragraph(3),
                    'location_id' => $allLocations->random()->id,
                    'founded_at' => fake()->numberBetween(1980, (int) now()->format('Y')),
                    'nip' => (string) fake()->numerify('##########'),
                ]);

                $offer = \App\Models\JobOffer::factory()->create([
                    'user_id' => $emp->id,
                    'company_id' => $employerCompany->id,
                    'company_name' => $employerCompany->name,
                    'category_id' => $allSubCategories->random()->id,
                    'location_id' => $allLocations->random()->id,
                    'is_approved' => true,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                    'expires_at' => $randomDate->copy()->addMonths(rand(1, 3)),
                ]);

                if (rand(1, 10) > 3) {
                    $offer->skills()->attach($allSkills->random(rand(2, 5))->pluck('id'));
                }
            }
            $this->command->info('Additional 30 historical job offers created across the last 12 months.');
        }

        $allOffers = \App\Models\JobOffer::all();
        foreach ($allOffers as $offer) {
            if ($offer->views_count === 0) {
                $offer->update([
                    'views_count' => rand(15, 2500),
                ]);
            }
        }
        $this->command->info('Random views added to all job offers.');
    }
}
