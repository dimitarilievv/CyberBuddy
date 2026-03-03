<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class CertificateService
{
    public function generate(Enrollment $enrollment): Certificate
    {
        $certificate = Certificate::firstOrCreate(
            ['user_id' => $enrollment->user_id, 'module_id' => $enrollment->module_id],
            [
                'certificate_number' => 'CB-' . strtoupper(Str::random(8)),
                'final_score' => $enrollment->progress_percentage,
                'issued_at' => now(),
            ]
        );

        // Генерирај PDF
        $pdf = Pdf::loadView('certificates.template', [
            'certificate' => $certificate,
            'user' => $enrollment->user,
            'module' => $enrollment->module,
        ]);

        $path = "certificates/{$certificate->certificate_number}.pdf";
        \Storage::disk('public')->put($path, $pdf->output());

        $certificate->update(['pdf_path' => $path]);

        return $certificate;
    }

    public function download(Certificate $certificate)
    {
        return \Storage::disk('public')->download($certificate->pdf_path);
    }
}
