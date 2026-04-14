<?php

namespace App\Services;

use App\Repositories\Interfaces\CertificateRepositoryInterface;
use App\Models\Enrollment;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CertificateService
{
    private CertificateRepositoryInterface $certificateRepo;

    public function __construct(CertificateRepositoryInterface $certificateRepo)
    {
        $this->certificateRepo = $certificateRepo;
    }

    public function generate(Enrollment $enrollment)
    {
        Log::info('Certificate generation started', [
            'user_id' => $enrollment->user_id,
            'module_id' => $enrollment->module_id,
        ]);

        $existing = $this->certificateRepo->findByEnrollment($enrollment->user_id, $enrollment->module_id);

        if ($existing) {
            Log::info('Certificate already exists', ['certificate_id' => $existing->id]);
            return $existing;
        }

        try {
            // Generate PDF
            $pdfPath = $this->generatePdf($enrollment);
            Log::info('PDF generated', ['pdf_path' => $pdfPath]);

            // Create certificate record
            $certificateNumber = 'CB-' . $enrollment->user_id . '-' . $enrollment->module_id . '-' . now()->format('YmdHis');

            $certificate = $this->certificateRepo->create([
                'user_id' => $enrollment->user_id,
                'module_id' => $enrollment->module_id,
                'certificate_number' => $certificateNumber,
                'issued_at' => Carbon::now(),
                'pdf_path' => $pdfPath,
                'final_score' => $enrollment->score ?? $enrollment->progress_percentage ?? 0,
            ]);

            Log::info('Certificate record created', ['certificate_id' => $certificate->id]);

            // ✅ CREATE NOTIFICATION DIRECTLY
            $this->createNotification($enrollment->user_id, $enrollment->module->title ?? 'Module', $certificate->id);

            return $certificate;
        } catch (\Throwable $e) {
            Log::error('Certificate generation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    private function createNotification(int $userId, string $moduleName, int $certificateId): void
    {
        try {
            Log::info('Creating notification', [
                'user_id' => $userId,
                'module_name' => $moduleName,
                'certificate_id' => $certificateId,
            ]);

            $notification = Notification::create([
                'user_id' => $userId,
                'title' => '🎉 Certificate Generated!',
                'message' => "Congratulations! You've completed '{$moduleName}' and earned a certificate!",
                'type' => 'certificate',  // ✅ Changed from 'info'
                'icon' => '📜',
                'action_url' => route('certificates.show', $certificateId),
                'is_read' => false,
            ]);

            Log::info('Notification created successfully', [
                'notification_id' => $notification->id,
                'user_id' => $userId,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to create notification', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

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
        $pdf->setPaper('a4', 'landscape');

        Storage::disk('public')->makeDirectory('certificates');
        Storage::disk('public')->put($filename, $pdf->output());

        return $filename;
    }

    public function download($certificate)
    {
        return Storage::disk('public')->download($certificate->pdf_path);
    }
}
