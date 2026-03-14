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
        DB::table('categories')->truncate();

        $categories = [
            'IT & Software' => [
                'Frontend Developer',
                'Backend Developer',
                'Full Stack Developer',
                'Mobile Developer',
                'DevOps Engineer',
                'QA Engineer',
                'Data Scientist',
                'Database Administrator',
                'Project Manager (IT)',
                'Architect',
                'System Administrator',
                'Embedded Developer',
                'Cybersecurity',
                'Game Developer',
                'Technical Writer'
            ],
            'Marketing' => [
                'Content Creator',
                'SEO Specialist',
                'Social Media Manager',
                'Email Marketer',
                'Brand Manager',
                'Copywriter',
                'Performance Marketer',
                'Public Relations',
                'Marketing Analyst',
                'Events Coordinator'
            ],
            'Finance' => [
                'Accountant',
                'Financial Analyst',
                'Tax Advisor',
                'Auditor',
                'Investment Banker',
                'Controller',
                'Real Estate Analyst',
                'Insurance Agent',
                'Billing Specialist',
                'Risk Manager'
            ],
            'Sales' => [
                'Sales Representative',
                'Account Manager',
                'Business Development',
                'Store Manager',
                'E-commerce Manager',
                'Telemarketer',
                'Real Estate Agent',
                'Logistics Specialist',
                'Merchandiser',
                'Support Sales'
            ],
            'Customer Service' => [
                'Customer Support',
                'Call Center Agent',
                'Technical Support',
                'Support Manager',
                'Guest Relations',
                'Client Success Manager',
                'Moderator',
                'Concierge',
                'Complaint Handler',
                'Personal Assistant'
            ],
            'Management' => [
                'CEO / Director',
                'Operations Manager',
                'Human Resources',
                'Team Leader',
                'Product Manager',
                'Office Manager',
                'Business Analyst',
                'Consultant',
                'Strategy Manager',
                'Branch Manager'
            ],
            'Design & Creative' => [
                'UI/UX Designer',
                'Graphic Designer',
                'Motion Designer',
                'Video Editor',
                'Illustrator',
                'Architect / Interior Designer',
                'Product Designer',
                'UX Researcher',
                'Art Director',
                '3D Artist'
            ]
        ];

        foreach ($categories as $parentName => $children) {
            $parentId = DB::table('categories')->insertGetId([
                'name' => $parentName,
                'slug' => \Illuminate\Support\Str::slug($parentName),
                'description' => "Main category for $parentName",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($children as $childName) {
                DB::table('categories')->insert([
                    'name' => $childName,
                    'slug' => \Illuminate\Support\Str::slug($childName),
                    'description' => "Subcategory $childName for $parentName",
                    'parent_id' => $parentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
