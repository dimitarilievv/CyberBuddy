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
        $existing = $this->certificateRepo->findByEnrollment($enrollment->user_id, $enrollment->module_id);
        if ($existing) {
            $needsGeneration = false;
            if (!$existing->pdf_path || !Storage::disk('public')->exists($existing->pdf_path)) {
                $needsGeneration = true;
            } else {
                try {
                    $fullPath = Storage::disk('public')->path($existing->pdf_path);
                    if (file_exists($fullPath)) {
                        $f = fopen($fullPath, 'rb');
                        $head = fread($f, 4);
                        fclose($f);
                        if ($head !== "%PDF") {
                            $needsGeneration = true;
                        }
                    } else {
                        $needsGeneration = true;
                    }
                } catch (\Throwable $e) {
                    $needsGeneration = true;
                }
            }

            if ($needsGeneration) {
                $pdfPath = $this->generatePdf($enrollment);
                $existing->pdf_path = $pdfPath;
                $existing->final_score = $enrollment->score ?? $enrollment->progress_percentage ?? $existing->final_score ?? 0;
                $existing->issued_at = Carbon::now();
                $existing->save();
            }

            return $existing;
        }

        $pdfPath = $this->generatePdf($enrollment);

        // generate a unique certificate number
        $certificateNumber = 'CB-' . $enrollment->user_id . '-' . $enrollment->module_id . '-' . now()->format('YmdHis');

        return $this->certificateRepo->create([
            'user_id' => $enrollment->user_id,
            'module_id' => $enrollment->module_id,
            'certificate_number' => $certificateNumber,
            'issued_at' => Carbon::now(),
            'pdf_path' => $pdfPath,
            'final_score' => $enrollment->score ?? $enrollment->progress_percentage ?? 0,
        ]);
    }

    /**
     * Generate PDF file (simple implementation without external libs)
     */
    private function generatePdf(Enrollment $enrollment): string
    {
        $filename = 'certificates/certificate_' . $enrollment->user_id . '_' . $enrollment->module_id . '.pdf';

        $certificate = (object)[
            'user' => $enrollment->user,
            'module' => $enrollment->module,
            'final_score' => $enrollment->score ?? $enrollment->progress_percentage ?? 0,
            'issued_at' => Carbon::now(),
            'id' => $enrollment->id,
            'certificate_number' => 'CB-' . $enrollment->user_id . '-' . $enrollment->module_id . '-' . now()->format('YmdHis'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificates.pdf', compact('certificate'));
        $pdf->setPaper('a4', 'landscape');  // ← must be here

        Storage::disk('public')->makeDirectory('certificates');
        Storage::disk('public')->put($filename, $pdf->output());

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
