<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'IT & Software',
                'slug' => 'it-software',
                'description' => 'Software development, IT support, and technology positions'
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Digital marketing, content creation, and advertising roles'
            ],
            [
                'name' => 'Finance',
                'slug' => 'finance',
                'description' => 'Accounting, financial analysis, and banking positions'
            ],
            [
                'name' => 'Healthcare',
                'slug' => 'healthcare',
                'description' => 'Medical, nursing, and healthcare administration'
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'Teaching, tutoring, and educational support roles'
            ],
            [
                'name' => 'Sales',
                'slug' => 'sales',
                'description' => 'Sales representatives, account managers, and business development'
            ],
            [
                'name' => 'Customer Service',
                'slug' => 'customer-service',
                'description' => 'Customer support and service positions'
            ],
            [
                'name' => 'Engineering',
                'slug' => 'engineering',
                'description' => 'Mechanical, electrical, and civil engineering roles'
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
