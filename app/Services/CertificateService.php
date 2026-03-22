<?php

namespace App\Services;

use App\Repositories\Interfaces\CertificateRepositoryInterface;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CertificateService
{
    private CertificateRepositoryInterface $certificateRepo;

    public function __construct(CertificateRepositoryInterface $certificateRepo)
    {
        $this->certificateRepo = $certificateRepo;
    }

    /**
     * Generate certificate for a completed enrollment
     */
    public function generate(Enrollment $enrollment)
    {
        $pdfPath = $this->generatePdf($enrollment);

        return $this->certificateRepo->create([
            'user_id' => $enrollment->user_id,
            'module_id' => $enrollment->module_id,
            'issued_at' => Carbon::now(),
            'pdf_path' => $pdfPath,
            'score' => $enrollment->score ?? 0,
        ]);
    }

    /**
     * Generate PDF file (mock implementation)
     */
    private function generatePdf(Enrollment $enrollment): string
    {
        $filename = 'certificates/certificate_' . $enrollment->user_id . '_' . $enrollment->module_id . '.pdf';

        // Here you could use barryvdh/laravel-dompdf or SnappyPDF
        Storage::disk('public')->put($filename, 'PDF content placeholder');

        return $filename;
    }

    /**
     * Download the certificate PDF
     */
    public function download($certificate)
    {
        return Storage::disk('public')->download($certificate->pdf_path);
    }
}
