<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Skill;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@jobstofind.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'date_of_birth' => '1990-01-01',
                'country' => 'Poland',
                'is_student' => false,
                'account_type' => 'admin',
                'cv_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'student_john',
                'first_name' => 'John',
                'last_name' => 'Kowalski',
                'email' => 'john.student@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'date_of_birth' => '2002-05-15',
                'country' => 'Poland',
                'is_student' => true,
                'account_type' => 'job_seeker',
                'cv_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'student_jane',
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'jane.doe@student.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'date_of_birth' => '2005-02-15',
                'country' => 'Poland',
                'is_student' => true,
                'account_type' => 'job_seeker',
                'cv_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'employer_anna',
                'first_name' => 'Anna',
                'last_name' => 'Nowak',
                'email' => 'anna.employer@company.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'date_of_birth' => '1985-03-20',
                'country' => 'Poland',
                'is_student' => false,
                'account_type' => 'employer',
                'cv_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'employer_tom',
                'first_name' => 'Tom',
                'last_name' => 'Smith',
                'email' => 'tom.employer@startup.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'date_of_birth' => '1988-07-10',
                'country' => 'United States',
                'is_student' => false,
                'account_type' => 'employer',
                'cv_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $allSkills = Skill::all()->pluck('id')->toArray();
        
        if (count($allSkills) > 0) {
            $seededUsers = User::whereIn('username', ['admin', 'student_john', 'student_jane'])->get();
            
            foreach ($seededUsers as $user) {
                $skillCount = rand(1, 100);
                if ($skillCount <= 40) {
                    $count = rand(1, 5);  
                } elseif ($skillCount <= 75) {
                    $count = rand(6, 10); 
                } elseif ($skillCount <= 95) {
                    $count = rand(11, 15); 
                } else {
                    $count = rand(16, 20); 
                }
                
                $randomSkills = array_rand(array_flip($allSkills), min($count, count($allSkills)));
                $user->skills()->attach(is_array($randomSkills) ? $randomSkills : [$randomSkills]);
            }
        }

        $jobSeekers = \App\Models\User::factory()->count(30)->create([
            'account_type' => 'job_seeker',
        ]);

        // $employers = \App\Models\User::factory()->count(15)->create([
        //     'account_type' => 'employer',
        // ]);

        if (count($allSkills) > 0) {
            foreach ($jobSeekers as $user) {
                $skillCount = rand(1, 100);
                if ($skillCount <= 40) {
                    $count = rand(1, 5);
                } elseif ($skillCount <= 75) {
                    $count = rand(6, 10);
                } elseif ($skillCount <= 95) {
                    $count = rand(11, 15);
                } else {
                    $count = rand(16, 20);
                }
                
                $randomSkills = array_rand(array_flip($allSkills), min($count, count($allSkills)));
                $user->skills()->attach(is_array($randomSkills) ? $randomSkills : [$randomSkills]);
            }
        }
    }
}
