<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Enrollment;
use App\Services\CertificateService;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    private CertificateService $certificateService;

    public function __construct(
        CertificateService $certificateService,
    ) {
        $this->certificateService = $certificateService;
    }

    /**
     * Листа на сертификати на логираниот корисник
     */
    public function index()
    {
        $certificates = Certificate::where('user_id', auth()->id())
            ->with('module')
            ->latest('issued_at')
            ->get();

        return view('certificates.index', compact('certificates'));
    }

    /**
     * Генерирај сертификат за завршен модул
     */
    public function generate(int $enrollmentId)
    {
        $enrollment = Enrollment::where('id', $enrollmentId)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->with(['user', 'module'])
            ->firstOrFail();

        // Провери дали веќе постои
        $existing = Certificate::where('user_id', auth()->id())
            ->where('module_id', $enrollment->module_id)
            ->first();

        if ($existing) {
            return redirect()->route('certificates.show', $existing)
                ->with('info', 'Сертификатот веќе е генериран!');
        }

        $certificate = $this->certificateService->generate($enrollment);

        return redirect()->route('certificates.show', $certificate)
            ->with('success', 'Сертификатот е генериран успешно! 🎉');
    }

    /**
     * Прикажи сертификат
     */
    public function show(Certificate $certificate)
    {
        // Само сопственикот или админот може да го види
        if ($certificate->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $certificate->load(['user', 'module']);

        return view('certificates.show', compact('certificate'));
    }

    /**
     * Преземи PDF на сертификатот
     */
    public function download(Certificate $certificate)
    {
        if ($certificate->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if (!$certificate->pdf_path || !\Storage::disk('public')->exists($certificate->pdf_path)) {
            // Регенерирај ако PDF не постои
            $enrollment = Enrollment::where('user_id', $certificate->user_id)
                ->where('module_id', $certificate->module_id)
                ->first();

            if ($enrollment) {
                $certificate = $this->certificateService->generate($enrollment);
            }
        }

        return $this->certificateService->download($certificate);
    }
}
