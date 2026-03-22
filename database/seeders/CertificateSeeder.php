<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Services\CertificateService;

class CertificateSeeder extends Seeder
{
    private CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function run(): void
    {
        $completedEnrollments = Enrollment::where('status', 'completed')->take(10)->get();

        foreach ($completedEnrollments as $enrollment) {
            $this->certificateService->generate($enrollment);
        }

        $this->command->info('CertificateSeeder complete.');
    }
}
