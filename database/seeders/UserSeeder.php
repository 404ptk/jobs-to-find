<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
    }
}
