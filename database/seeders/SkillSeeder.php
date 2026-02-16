<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            'PHP', 'Python', 'JavaScript', 'Java', 'C#', 'C++', 'Ruby', 'Go', 'Swift', 'Kotlin',
            'TypeScript', 'Rust', 'SQL', 'HTML', 'CSS', 'Sass', 'Less', 'Bootstrap', 'Tailwind CSS',
            'React', 'Angular', 'Vue.js', 'Node.js', 'Express.js', 'Laravel', 'Symfony', 'Django',
            'Flask', 'Spring Boot', 'ASP.NET', 'MySQL', 'PostgreSQL', 'SQLite', 'MongoDB', 'Redis',
            'Oracle', 'SQL Server', 'Git', 'Docker', 'Kubernetes', 'Jenkins', 'AWS', 'Azure',
            'Google Cloud', 'Linux', 'Bash', 'PowerShell', 'Nginx', 'Apache', 'Communication',
            'Teamwork', 'Problem Solving', 'Critical Thinking', 'Time Management', 'Leadership',
            'Adaptability', 'Creativity', 'Emotional Intelligence', 'UI/UX Design', 'Figma',
            'Adobe XD', 'Photoshop', 'Illustrator', 'Agile', 'Scrum', 'Kanban', 'Jira', 'Trello', 'Asana'
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(['name' => $skill]);
        }
    }
}
