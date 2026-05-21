<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');
        DB::table('categories')->truncate();
        DB::statement('PRAGMA foreign_keys = ON');

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
                'IT Architect',
                'System Administrator',
                'Embedded Developer',
                'Cybersecurity',
                'Game Developer',
                'Technical Writer',
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
                'Events Coordinator',
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
                'Risk Manager',
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
                'Support Sales',
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
                'Personal Assistant',
            ],
            'Management' => [
                'CEO / Director',
                'Operations Manager',
                'Human Resources',
                'Team Leader',
                'Product Manager',
                'Operations / Office Manager',
                'Business Analyst',
                'Consultant',
                'Strategy Manager',
                'Branch Manager',
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
                '3D Artist',
            ],
            'Healthcare & Medical' => [
                'Nurse',
                'Doctor / Physician',
                'Pharmacist',
                'Physiotherapist',
                'Medical Assistant',
                'Dentist',
                'Psychologist',
                'Lab Technician',
                'Paramedic',
                'Veterinarian',
            ],
            'Education & Training' => [
                'Teacher',
                'Tutor / Instructor',
                'Lecturer',
                'Linguist / Translator',
                'Education Consultant',
                'Trainer',
                'Childcare Provider',
                'School Administrator',
                'Researcher',
                'Coach',
            ],
            'Engineering & Construction' => [
                'Civil Engineer',
                'Electrical Engineer',
                'Mechanical Engineer',
                'Structural Engineer',
                'Construction Manager',
                'Construction Architect',
                'Site Supervisor',
                'Surveyor',
                'BIM Coordinator',
                'Project Engineer',
            ],
            'Logistics & Transport' => [
                'Driver (Truck/Van)',
                'Logistics Coordinator',
                'Warehouse Worker',
                'Supply Chain Manager',
                'Fleet Manager',
                'Dispatcher',
                'Freight Forwarder',
                'Inventory Controller',
                'Shipping Clerk',
                'Delivery Specialist',
            ],
            'Human Resources (HR)' => [
                'Recruiter',
                'HR Manager',
                'HR Specialist',
                'Payroll Specialist',
                'Compensation & Benefits',
                'HR Coordinator',
                'Trainer / L&D',
                'Talent Acquisition',
                'Employee Relations',
                'Employer Branding',
            ],
            'Legal & Administrative' => [
                'Lawyer / Attorney',
                'Legal Advisor',
                'Paralegal',
                'Compliance Officer',
                'Legal Administrative Assistant',
                'Secretary / Receptionist',
                'Legal Office Manager',
                'Notary',
                'Corporate Secretary',
                'Data Protection Officer',
            ],
            'Hospitality & Tourism' => [
                'Chef / Cook',
                'Waiter / Waitress',
                'Bartender',
                'Hotel Manager',
                'Receptionist',
                'Event Planner',
                'Travel Agent',
                'Housekeeper',
                'Barista',
                'Tour Guide',
            ],
            'Media & Communication' => [
                'Journalist',
                'Editor',
                'Photographer',
                'Videographer',
                'PR Specialist',
                'Radio / TV Presenter',
                'Social Media Specialist',
                'Blogger / Vlogger',
                'Communications Manager',
                'Language Translator',
            ],
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
