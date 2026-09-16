<?php

namespace App\Http\Controllers;

use App\Models\SiteCopy;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResumeController extends Controller
{
    public function __invoke(): BinaryFileResponse|StreamedResponse
    {
        abort_unless(Schema::hasTable('site_copies'), 404);

        $copy = SiteCopy::current();
        $binary = $copy->resumeContents();

        abort_if($binary === null || $binary === '', 404);

        $filename = $copy->resumeDownloadName();
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Length' => (string) strlen($binary),
            'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Resume-Source' => $copy->hasStoredResumePayload() ? 'admin-upload' : 'fallback',
        ];

        return response()->streamDownload(
            static function () use ($binary): void {
                echo $binary;
            },
            $filename,
            $headers,
        );
    }
}
