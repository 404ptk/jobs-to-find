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
                'requirements' => '- 5+ years of experience in web development\n- Strong knowledge of PHP, Laravel, JavaScript, React\n- Experience with RESTful APIs and microservices\n- Knowledge of SQL and NoSQL databases\n- Excellent problem-solving skills\n- Good communication skills in English',
                'company_name' => 'TechVision Solutions',
                'salary_range' => '12000 - 18000 PLN',
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
                'requirements' => '- 1+ year of experience with HTML, CSS, JavaScript\n- Basic knowledge of React or Vue.js\n- Understanding of responsive design\n- Willingness to learn and grow\n- Team player attitude\n- English level: B2 or higher',
                'company_name' => 'TechVision Solutions',
                'salary_range' => '5000 - 7000 PLN',
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
                'requirements' => '- Currently studying Marketing, Business, or related field\n- Basic knowledge of social media platforms\n- Creative thinking and good writing skills\n- Proficiency in MS Office\n- Eagerness to learn digital marketing tools\n- Good English communication skills',
                'company_name' => 'TechVision Solutions',
                'salary_range' => '3000 - 4000 PLN',
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
