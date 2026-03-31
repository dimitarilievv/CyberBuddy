<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Services\CertificateService;
use Illuminate\Support\Facades\App;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $certificateService = App::make(CertificateService::class);

        // Get ALL completed enrollments
        $completedEnrollments = Enrollment::where('status', 'completed')->get();

        if ($completedEnrollments->isEmpty()) {
            $this->command->warn('No completed enrollments found.');
            return;
        }

        $this->command->info("Found {$completedEnrollments->count()} completed enrollments.");

        $generated = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($completedEnrollments as $enrollment) {
            // Check if certificate already exists for this user and module
            $existingCertificate = Certificate::where('user_id', $enrollment->user_id)
                ->where('module_id', $enrollment->module_id)
                ->first();

            if ($existingCertificate) {
                $skipped++;
                $this->command->line("⊘ Skipping - Certificate already exists for user {$enrollment->user_id} on module {$enrollment->module_id}");
                continue;
            }

            try {
                // Generate certificate (you may need to modify the service to accept enrollment)
                $certificate = $certificateService->generate($enrollment);

                if ($certificate) {
                    $generated++;
                    $userName = $enrollment->user->name ?? 'Unknown';
                    $moduleTitle = $enrollment->module->title ?? 'Unknown';
                    $this->command->line("✓ Generated certificate for {$userName} - {$moduleTitle}");
                } else {
                    $failed++;
                    $this->command->error("✗ Failed to generate certificate for enrollment ID: {$enrollment->id}");
                }

            } catch (\Exception $e) {
                $failed++;
                $this->command->error("✗ Error generating certificate for enrollment ID {$enrollment->id}: {$e->getMessage()}");
            }
        }

        $this->command->info("\n" . str_repeat('=', 50));
        $this->command->info("CertificateSeeder Summary:");
        $this->command->info("  ✓ Generated: {$generated}");
        $this->command->info("  ⊘ Skipped (already exist): {$skipped}");
        $this->command->info("  ✗ Failed: {$failed}");
        $this->command->info("  📊 Total certificates in database: " . Certificate::count());
        $this->command->info(str_repeat('=', 50));
    }
}
