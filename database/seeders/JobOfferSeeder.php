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
        // Get employer_anna's user ID
        $employer = User::where('username', 'employer_anna')->first();
        
        if (!$employer) {
            $this->command->error('User employer_anna not found. Please run UserSeeder first.');
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
                'expires_at' => Carbon::now()->addMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('job_offers')->insert($jobOffers);

        $this->command->info('Job offers created successfully for employer_anna!');
    }
}
