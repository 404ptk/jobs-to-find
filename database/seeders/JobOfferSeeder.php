<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class JobOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employer = User::where('username', 'employer_anna')->first();
        $employerTom = User::where('username', 'employer_tom')->first();
        
        if (!$employer || !$employerTom) {
            $this->command->error('Users not found. Please run UserSeeder first.');
            return;
        }

        $jobOffers = [
            [
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
                'category_id' => 1, // IT & Software
                'location_id' => 1, // Warsaw
                'is_active' => true,
                'is_approved' => true,
                'expires_at' => Carbon::now()->addMonths(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
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
                'category_id' => 1, // IT & Software
                'location_id' => 7, // Remote
                'is_active' => true,
                'is_approved' => true,
                'expires_at' => Carbon::now()->addMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
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
                'category_id' => 2, // Marketing
                'location_id' => 2, // Krakow
                'is_active' => true,
                'is_approved' => true,
                'expires_at' => Carbon::now()->addMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $pendingOffers = [
            [
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
                'category_id' => 1, // IT & Software
                'location_id' => 7, // Remote
                'is_active' => true,
                'is_approved' => false,
                'expires_at' => Carbon::now()->addMonths(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
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
                'category_id' => 3, // Design & Creative
                'location_id' => 7, // Remote
                'is_active' => true,
                'is_approved' => false,
                'expires_at' => Carbon::now()->addMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('job_offers')->insert($jobOffers);
        DB::table('job_offers')->insert($pendingOffers);

        $this->command->info('Job offers created successfully for employer_anna and employer_tom!');

        $employers = User::where('account_type', 'employer')->get();

        if ($employers->count() > 0) {
            foreach ($employers as $emp) {
                \App\Models\JobOffer::factory()->count(rand(2, 5))->create([
                    'user_id' => $emp->id,
                    'is_approved' => true,
                    'company_name' => fake()->company(),
                ]);

                \App\Models\JobOffer::factory()->count(rand(0, 2))->create([
                    'user_id' => $emp->id,
                    'is_approved' => false,
                    'company_name' => fake()->company(),
                ]);
            }
            $this->command->info('Additional job offers generated for other employers.');
        }
    }
}
