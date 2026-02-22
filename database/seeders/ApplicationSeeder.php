<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ApplicationSeeder extends Seeder
{
  public function run(): void
  {
    Storage::disk('public')->makeDirectory('cvs');

    $minimalPdf = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] >>\nendobj\nxref\n0 4\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \ntrailer\n<< /Size 4 /Root 1 0 R >>\nstartxref\n190\n%%EOF";

    $jobSeekers = User::where('account_type', 'job_seeker')->get();
    $approvedOffers = JobOffer::where('is_approved', true)->where('is_active', true)->get();

    if ($jobSeekers->isEmpty() || $approvedOffers->isEmpty()) {
      $this->command->error('No job seekers or approved offers found. Run UserSeeder and JobOfferSeeder first.');
      return;
    }

    $firstNames = ['Adam', 'Maria', 'Piotr', 'Katarzyna', 'Michał', 'Aleksandra', 'Jakub', 'Natalia', 'Tomasz', 'Ewa', 'Paweł', 'Magdalena', 'Krzysztof', 'Agnieszka', 'Łukasz', 'Joanna', 'Marcin', 'Anna', 'Kamil', 'Monika'];
    $lastNames = ['Wiśniewski', 'Wójcik', 'Kowalczyk', 'Kamiński', 'Lewandowski', 'Zielińska', 'Szymańska', 'Woźniak', 'Dąbrowska', 'Kozłowski', 'Jankowska', 'Mazur', 'Kwiatkowski', 'Krawczyk', 'Pawłowski', 'Michalska', 'Nowakowska', 'Adamczyk', 'Dudek', 'Zając'];

    $applicationCount = 0;

    foreach ($approvedOffers as $offer) {
      $numApplications = rand(0, 6);
      $usedSeekers = [];

      for ($i = 0; $i < $numApplications; $i++) {
        $seeker = $jobSeekers->whereNotIn('id', $usedSeekers)->random();
        if (!$seeker)
          break;
        $usedSeekers[] = $seeker->id;

        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];

        $cvPath = null;
        if (rand(1, 100) <= 60) {
          $filename = 'cvs/cv_' . strtolower($firstName) . '_' . strtolower(str_replace(['ś', 'ń', 'ó', 'ł', 'ą', 'ę', 'ć', 'ź', 'ż'], ['s', 'n', 'o', 'l', 'a', 'e', 'c', 'z', 'z'], $lastName)) . '_' . rand(1000, 9999) . '.pdf';
          Storage::disk('public')->put($filename, $minimalPdf);
          $cvPath = $filename;
        }

        Application::create([
          'user_id' => $seeker->id,
          'job_offer_id' => $offer->id,
          'first_name' => $firstName,
          'last_name' => $lastName,
          'email' => strtolower($firstName) . '.' . strtolower(str_replace(['ś', 'ń', 'ó', 'ł', 'ą', 'ę', 'ć', 'ź', 'ż'], ['s', 'n', 'o', 'l', 'a', 'e', 'c', 'z', 'z'], $lastName)) . '@example.com',
          'cv_path' => $cvPath,
          'status' => ['pending', 'reviewed', 'accepted', 'rejected'][rand(0, 3)],
          'created_at' => now()->subDays(rand(0, 30)),
          'updated_at' => now(),
        ]);

        $applicationCount++;
      }
    }

    $this->command->info("Created {$applicationCount} sample applications with CV attachments.");
  }
}
